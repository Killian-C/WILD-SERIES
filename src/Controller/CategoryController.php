<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route ("/categories", name="category_")
 */

class CategoryController extends AbstractController
{
    /**
     * @Route ("/", name="index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route ("/{categoryName}", name="show")
     */
    public function show(string $categoryName): Response
    {
        $serieByCategoryNbMax = 3;
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);
        if (!$category) {
            throw $this->createNotFoundException(
                '404 : Aucune catégorie ne correspond à ' . $categoryName
            );
        } else {
            $programs = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findBy(
                    ['category' => $category],
                    ['id' => 'DESC'],
                    $serieByCategoryNbMax
                );
        }

        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'category' => $category,
        ]);
    }
}