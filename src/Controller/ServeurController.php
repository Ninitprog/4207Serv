<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Document;
use App\Entity\Acces;



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
    public function affiche(Request $request, EntityManagerInterface $manager, SessionInterface $session): Response
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
                $session->set('nomVar', $utilisateur->getId());
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
    public function creerutilisateur(SessionInterface $session, EntityManagerInterface $manager ): Response
    {
        $vs = $session -> get('nomVar');
        if ($vs!=0){
            $user=$manager->getRepository(Utilisateur::class)->findOneById($vs);
            if ($user->getNom()=="admin"){
                return $this->render('serveur/creerutilisateur.html.twig', [
                    'controller_name' => 'ServeurController',
                ]);
            }
            else{
                $reponse2="accès interdit";
                return $this->render('serveur/affiche.html.twig', [
                    'Message' => $reponse2,
                ]);
            }
        }
        else{
            return $this->render('serveur/index.html.twig', [
                'controller_name' => 'ServeurController',
            ]);
        }
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
    public function afficheUti(EntityManagerInterface $manager, SessionInterface $session): Response
    {
        $vs = $session -> get('nomVar');
        if ($vs!=0){
            $user=$manager->getRepository(Utilisateur::class)->findOneById($vs);
            if ($user->getNom()=="admin"){
                $mesUtilisateurs=$manager->getRepository(Utilisateur::class)->findAll();
                return $this->render('serveur/afficheUti.html.twig',['lst_utilisateurs' => $mesUtilisateurs]);
            }
            else{
                $reponse2="accès interdit";
                return $this->render('serveur/affiche.html.twig', [
                    'Message' => $reponse2,
                ]);
            }
        }
        else {
            return $this->render('serveur/index.html.twig', [
                'controller_name' => 'ServeurController',
            ]);
        }
}
/**
     * @Route("/serveur/session", name="serveur/session")
     */
    public function session(SessionInterface $session, EntityManagerInterface $manager): Response
    {
        $vs = $session -> get('nomVar');
        $user=$manager->getRepository(Utilisateur::class)->findOneById($vs);
        return $this->render('serveur/session.html.twig',['name' => $user->getNom()]);
}

/**
* @Route("/supprimerUtilisateur/{id}",name="supprimer_Utilisateur")
*/
public function supprimerUtilisateur(EntityManagerInterface $manager,Utilisateur $editutil): Response {
    $manager->remove($editutil);
    $manager->flush();
    // Affiche de nouveau la liste des utilisateurs
    return $this->redirectToRoute ('serveur/afficheUti');
 }
 /**
     * @Route("/serveur/deco", name="serveur/deco")
     */
    public function deco(SessionInterface $session): Response
    {
        $session->clear();
        return $this->render('serveur/index.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
        
}

/**
     * @Route("/server/ajouteFichier", name="server/ajouteFichier")
     */
    public function ajouteFichier(Request $request,EntityManagerInterface $manager,SessionInterface $session ): Response
    {
        $uploadFiles = '/home/etudrt/serveChanrion/public';
        $fifi= $request -> request -> get("fichier");
        $fifi = $_FILES['fichier']['tmp_name'];
        $name = basename($_FILES['fichier']['name']);
        move_uploaded_file($fifi, "$uploadFiles/$name");
        $userId = $request -> request -> get("identifiant");

        $newDOC = new Document();
        $newDOC -> setChemin($name);
        $date = new \DateTime("now");
        $newDOC -> setDate($date);
        $actif = TRUE;
        $newDOC -> setActif($actif);
        $manager->persist($newDOC);
        $manager->flush();

        $newAcces = new Acces (); 
        $uti=($utilisateur = $manager -> getRepository(Utilisateur::class)->findOneById($userId));
        $newAcces->setUtilisateur($uti);
        $droit=NULL;
        $newAcces->setAutorisation($droit);
        $newAcces->setDocument($name);
        $manager->persiste($newAcces);
        $manager->flush();




        return $this->render('serveur/insertFichier.html.twig', ['controller_name' => 'ServeurController']);

}
/**
     * @Route("/serveur/inserFichier", name="serveur/inserFichier")
     */
    public function insertFichier(SessionInterface $session): Response
    {
        $vs = $session -> get('nomVar');
        if ($vs!=0){
            return $this->render('serveur/insertFichier.html.twig', ['controller_name' => 'ServeurController']);
        }
        else {
            return $this->render('serveur/index.html.twig', [
                'controller_name' => 'ServeurController',
            ]);
        }
}

/**
     * @Route("/serveur/listeFichier", name="serveur/listeFichier")
     */
    public function listeFichier(SessionInterface $session): Response
    {
        $vs = $session -> get('nomVar');
        if ($vs!=0){
            return $this->render('serveur/listeFichier.html.twig', ['controller_name' => 'ServeurController']);
        }
        else {
            return $this->render('serveur/index.html.twig', [
                'controller_name' => 'ServeurController',
            ]);
        }
}
 
}