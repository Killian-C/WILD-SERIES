<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/my-profile", name="user_")
 */

class UserController extends AbstractController
{
    /**
     * @Route("/", name="profile")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }
}
