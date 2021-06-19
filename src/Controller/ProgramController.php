<?php


namespace App\Controller;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Service\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route ("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route ("/", name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route ("/new", name="new")
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('killian.couraillon@hotmail.fr')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route ("/{slug}", methods={"GET"}, name="show")
     */
    public function show(Program $program): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program->getId().' found in program\'s table.'
            );
        }

        $seasons = $program->getSeasons();

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @Route ("/{slug}/seasons/{season}", name="season_show")
     */
    public function showSeason(Program $program, Season $season): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$programId.' found in program\'s table.'
            );
        }

        $episodes = $season->getEpisodes();

        return $this->render('program/season_show.html.twig', [
            'program'  => $program,
            'season'   => $season,
            'episodes' => $episodes,
        ]);
    }

    /**
     * @Route ("/{program_slug}/seasons/{season}/episodes/{episode_slug}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping":{"program_slug":"slug"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping":{"episode_slug":"slug"}})
     */
    public function showEpisode(Request $request, Program $program, Season $season, Episode $episode)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('program_episode_show', [
                'program_slug' => $program->getSlug(),
                'season'       => $season->getId(),
                'episode_slug' => $episode->getSlug(),
            ]);
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season'  => $season,
            'episode' => $episode,
            'form'    => $form->createView(),
        ]);
    }
}