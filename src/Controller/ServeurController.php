<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ServeurController extends AbstractController
{
    /**
     * @Route("/serveur", name="serveur")
     */
    public function index(): Response
    {
        return $this->render('serveur/index.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
}

    /**
     * @Route("/serveur/affiche", name="serveur/affiche")
     */
    public function affiche(Request $request): Response
    {
        $identifiant = $request -> request -> get("identifiant");
        $password = $request -> request -> get("password");
        if (($identifiant=="root") && ($password=="toor")){
            $reponse = "acces autorise";
         } 
         else{
             $reponse = "erreur";
         }
        return $this->render('serveur/affiche.html.twig', [
            'Message' => $reponse,
        ]);
}

/**
     * @Route("/serveur/creerutilisateur", name="serveur/creerutilisateur")
     */
    public function creerutilisateur(): Response
    {
        return $this->render('serveur/creerutilisateur.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
}

}