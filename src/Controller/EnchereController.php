<?php

namespace App\Controller;

use App\Entity\EnchereFournisseur;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Enchere;
use App\Form\AddEnchereType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EnchereRepository;

class EnchereController extends AbstractController
{

    #[Route(path: '/enchere', name: 'app_enchere')]
    public function index(EnchereRepository $enchereRepo): Response
    {
        $this->denyAccessUnlessGranted(attribute: 'IS_AUTHENTICATED_FULLY'); //verif auth

        $client = HttpClient::create(defaultOptions: ['verify_peer' => false, 'verify_host' => false]); //creation clien qui peut faire des requettes vers l'api sans verif certif ssl
        $res  = $client->request(method: 'GET', url: 'https://localhost:44335/Fournisseurs/AllFournisseurs');

        $allfournisseur = $res->toArray();

        date_default_timezone_set('Europe/Paris');
        $dateactuelle = date('d-M-y  H:i');

        $user = $this->getUser();//recup du nom d'user

        $societe=null;
        foreach ( $allfournisseur as $fournisseur)// relation entre user symfony et le fournisseur
        {
            if ($fournisseur['email'] == strval(value: $user->getUserIdentifier() )){
                $societe_Fournisseur = $fournisseur['societe'];
                $Id_fournisseur = $fournisseur['id'];
            }
            if (strval(value: $user->getUserIdentifier()) == 'enzo')
            {
                $societe_Fournisseur = (strval(value: $user->getUserIdentifier()));
                $Id_fournisseur = 0;
            }
        }
        if ($societe_Fournisseur===null) // si le user ne correspond à aucun fournisseur alors on revient sur la page de base
        {
            return $this->redirectToRoute(route: 'app_page');
        }

        //recuperation du panier global
        $res2 = $client->request(method: 'GET', url: 'https://localhost:44335/Panier_Global/AllPanierGlobal');
        $allpanierglobal= $res2 ->toArray();
        $dernierPanierGlobal = end(array: $allpanierglobal);

        $derniereenchere = $enchereRepo->findOneBy([], ['id' => 'DESC']);



        $res4 = $client->request(method: 'GET', url: 'https://localhost:44335/Panier_Global_Details/GetGlobalDetailByPanier?ID='.$dernierPanierGlobal['id'].''); //appel a l'api
        $panierglobaldetail = $res4->toArray();


        $res5 = $client->request(method: 'GET', url: 'https://localhost:44335/Reference/AllReference'); //appel a l'api
        $AllReferences = $res5->toArray();

        $req6 = $client->request(method: 'GET', url: 'https://localhost:44335/Fournisseur_Reference/GetFournisseurReferenceByFournisseur?ID=1');
        $referenceDuFournisseur = $req6->toArray();

        $referenceFiltres = [];

        foreach ($referenceDuFournisseur as $referenceFournisseurTmp) {
            foreach ($AllReferences as $referenceTmp) {
                if ($referenceFournisseurTmp["id_references"] == $referenceTmp["id"]) {
                    array_push($referenceFiltres, $referenceTmp);
                }
            }
        }

        dump($derniereenchere);






            return $this->render(view: 'enchere/index.html.twig', parameters: [
            'controller_name' => 'EnchereController',
            // test de connection a l'api   'status' => strval($res->getStatusCode()),// creer un parametre status converti en string pour affichage en html
            'allfournisseur' => $allfournisseur,
            'idfournisseur' => $Id_fournisseur,
            /*'refbypanier' => $refbypanier,*/
            'panierglobaldetail' => $panierglobaldetail,
            'societe_Fournisseur' => $societe_Fournisseur,
            'encheres' => $derniereenchere,
            'enchere' => $enchereRepo ->findAll(),
            'dateactuelle' => $dateactuelle,
            'AllReferences' => $referenceFiltres
        ]);
    }

    #[Route(path: '/enchere/ajouter_enchere', name: 'ajouter_enchere')]
    public function AjouterEnchere(\Symfony\Component\HttpFoundation\Request $request,EntityManagerInterface $entityManager,EnchereRepository $enchereRepo): Response
    {

        $this->denyAccessUnlessGranted(attribute: 'IS_AUTHENTICATED_FULLY'); //verif auth

        $user = $this->getUser();
        if (strval(value: $user->getUserIdentifier()) != 'mauris@mauris.com')
        {
            return $this->redirectToRoute(route: 'app_page');
        }
        $client = HttpClient::create(defaultOptions: ['verify_peer' => false, 'verify_host' => false]); //creation clien qui peut faire des requettes vers l'api sans verif certif ssl
        //recuperation du panier global
        $res2 = $client->request(method: 'GET', url: 'https://localhost:44335/Panier_Global/AllPanierGlobal');
        $allpanierglobal= $res2 ->toArray();
        $dernierPanierGlobal = end(array: $allpanierglobal);
        date_default_timezone_set('Europe/Paris');
        $semaineactuelle = date('W');
        $derniereenchere = $enchereRepo->findOneBy([], ['id' => 'DESC']);


        $enchere = new Enchere();
        $form = $this->createForm(type: AddEnchereType::class, data: $enchere);
        $form->handleRequest(request: $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enchere = $form->getData();

            $entityManager->persist(entity: $enchere);
            $entityManager->flush();

            return $this->redirectToRoute(route: 'app_enchere');
        }
        return $this->render(view: 'enchere/ajouter_enchere.html.twig', parameters: [

            'allpanierglobal' => $allpanierglobal,
            'dernierPanierGlobal' => $dernierPanierGlobal,
            'form' => $form->createView(),
            'semaineactuelle' => $semaineactuelle,
            'derniereenchere' => $derniereenchere,
        ]);

    }

    #[Route(path: '/encherir', name: 'encherir')]
    public function encherir(Request $request, EntityManagerInterface $entityManager,EnchereRepository $enchereRepo) {
        $parameters = json_decode($request->getContent(), true);
        $user = $this->getUser();
        $fournisseur = strval(value: $user->getUserIdentifier());

        //$derniereenchere = $enchereRepo->findOneBy([], ['id' => 'DESC']);


        $enchereFournisseur=new EnchereFournisseur();
        $enchereFournisseur->setIdEnchereId($parameters['id_enchere']);
        $enchereFournisseur->setPrix($parameters['prix']);
        $enchereFournisseur->setFournisseur($fournisseur) ;
        $enchereFournisseur->setProduit($parameters['reference']);
        $enchereFournisseur->setIdPanierglobaldetail($parameters['idpanierglobal']);

        $entityManager->persist($enchereFournisseur);
        $entityManager->flush();

        $client = HttpClient::create(defaultOptions: ['verify_peer' => false, 'verify_host' => false]);
        $res  = $client->request(method: 'GET', url: 'https://localhost:44335/Fournisseurs/AllFournisseurs');
        $allfournisseur = $res->toArray();



        /*foreach ( $allfournisseur as $fournisseur)// relation entre user symfony et le fournisseur
        {
            if ($fournisseur['email'] == strval(value: $user->getUserIdentifier())) {

                $Id_fournisseur = $fournisseur['id'];
            }
        }

        $client->request( 'POST',  'https://localhost:44335/Panier_Fournisseurs',
            ['body' => [
                "id_fournisseurs"=>$Id_fournisseur,
                "puht"=>$parameters['prix'],
                "id_panier_global_detail"=>$parameters['idpanierglobal'],

            ]]);*/
////
//
//         $client->request( 'POST',  'https://localhost:44362/OffreFournisseur',
//            ['headers' => [
//                  "iD_FOURNISSEURS"=>$Id_fournisseur,
//                "offres"=>$parameters['prix'],
//                "iD_PANIER_GLOBALS_DETAILS"=>$parameters['idpanierglobaledetails'],
//
//                ]]);



        return new JsonResponse(array('name' => $parameters['prix']));
    }

}
