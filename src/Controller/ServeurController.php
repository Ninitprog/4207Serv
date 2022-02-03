<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;


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
    public function affiche(Request $request, EntityManagerInterface $manager): Response
    {
        $identifiant = $request -> request -> get("identifiant");
        $password = $request -> request -> get("password");
        $utilisateur = $manager -> getRepository(utilisateur :: class) -> findOneBy([ 'Nom' => $identifiant]);
        if ($utilisateur == NULL){
            $reponse2 = "utilisateur inconnu";
        }
        else {
            $code = $utilisateur -> getCode();
            if ($code == $password){
                $reponse2 = "acces autorise";
            } 
            else{
                 $reponse2 = "erreur";
            }
        }
        return $this->render('serveur/affiche.html.twig', [
            'Message' => $reponse2,
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
/**
     * @Route("/serveur/ajoututilisateur", name="/serveur/ajoututilisateur")
     */
    public function ajoututilisateur(Request $request, EntityManagerInterface $manager): Response
    {
        $nom = new Utilisateur();
        $Prenom = new Utilisateur();
        $MDP = new Utilisateur ();
        $nom = $request -> request -> get("nom");
        $Prenom = $request -> request -> get("Prenom");
        $MDP = $request -> request -> get("MDP");
        $Nom->setNom('$Nom');
        $manager->persist($Nom);
        $Prenom->setPrenom('$Prenom');
        $manager->persist($Prenom);
        $MDP->setCode('$MDP');
        $manager->persist($MDP);
        $manager->flush;

        $text = "ajout effectuer";

       return $this->render('serveur/ajoututilisateur.html.twig', [
            'text' => $text,
        ]);
}

}