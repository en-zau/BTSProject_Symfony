<?php

namespace App\Controller;

use App\Repository\EnchereFournisseurRepository;
use App\Repository\EnchereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_page')]
    public function index(EnchereFournisseurRepository $enchereRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); //verif auth
        $user = $this->getUser();

        $encheres = $enchereRepo->findAll();



        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
            'user_name' => strval($user->getUserIdentifier()),
            'encheres' => $encheres
        ]);
    }

}