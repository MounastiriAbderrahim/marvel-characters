<?php

namespace App\Controller;

use App\Entity\Character;
use App\Service\MarvelService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarvelController extends AbstractController
{
    private $marvelService;

    function __construct(MarvelService $marvelService)
    {
        $this->marvelService = $marvelService;
    }

    /**
     * @Route("/", name="marvel_list")
     * Show list of characters
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        // Paginate list of characters
        $characters = $paginator->paginate(
            $entityManager->getRepository(Character::class)->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('marvel/index.html.twig', ['characters' => $characters,]);
    }

    /**
     * @Route("/import", name="marvel_import")
     * Import list of characters
     */
    public function import(): Response
    {
        $response = $this->marvelService->import();
        $this->addFlash($response['type'], $response['message']);
        return $this->redirectToRoute('marvel_list');
    }
}
