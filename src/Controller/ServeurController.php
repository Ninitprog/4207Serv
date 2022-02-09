<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


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
            if (password_verify($password,$code)){
                $reponse2 = "acces autorise";
                return $this->redirectToRoute ('serveur/session');
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
        $newUti = new Utilisateur();
        $nom = $request -> request -> get("nom");
        $Prenom = $request -> request -> get("Prenom");
        $MDP = $request -> request -> get("MDP");
        $MDP = (password_hash($MDP, PASSWORD_DEFAULT));
        $newUti->setNom($nom);
        $manager->persist($newUti);
        $newUti->setPrenom($Prenom);
        $manager->persist($newUti);
        $newUti->setCode($MDP);
        $manager->persist($newUti);
        $manager->flush();

        return $this->redirectToRoute ('serveur/creerutilisateur');
}

/**
     * @Route("/serveur/afficheUti", name="serveur/afficheUti")
     */
    public function afficheUti(EntityManagerInterface $manager): Response
    {
        $mesUtilisateurs=$manager->getRepository(Utilisateur::class)->findAll();
        return $this->render('serveur/afficheUti.html.twig',['lst_utilisateurs' => $mesUtilisateurs]);
}
/**
     * @Route("/serveur/session", name="serveur/session")
     */
    public function session(SessionInterface $session): Response
    {
        return $this->render('serveur/session.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
}

}