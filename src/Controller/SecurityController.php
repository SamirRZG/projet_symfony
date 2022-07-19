<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    /**
    *@Route("/inscription", name="inscription") 
    */
    public function inscription( Request $request, EntityManagerInterface $manager , UserPasswordHasherInterface $hasher  )
    {

        $utilisateur= new Utilisateur();

        $form=$this->createForm(InscriptionType::class, $utilisateur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mdp=$hasher->hashPassword($utilisateur, $utilisateur->getPassword() );

            $utilisateur->setPassword($mdp);

            $manager->persist($utilisateur);
            $manager->flush();

            $this->addFlash('success', ' Félicitation, vous êtes à présent inscrit. Connectez vous !!!!');

            

          
        }

     
     
     return $this->render("security/inscription.html.twig",[
        'form'=>$form->createView()
     ] ) ;
    }



    /**
    *@Route("/login", name="login") 
    */
    public function login()
    {
       // dd('coucou');
     return $this->render("security/login.html.twig",[] ) ;
    }


    /**
    *@Route("/logout", name="logout") 
    */
    public function logout()
    {
     
 
    }











}// fermeture du controller
