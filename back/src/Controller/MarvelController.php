<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarvelController extends AbstractController
{
    /**
     * @Route("/", name="app_marvel")
     */
    public function index(): Response
    {
        return $this->render('marvel/index.html.twig', [
            'controller_name' => 'MarvelController',
        ]);
    }
}
