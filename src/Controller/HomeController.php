<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Commande;
use App\Entity\Commentaire;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Service\Panier\PanierService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class HomeController extends AbstractController
{

    /**
    *@Route("/", name="home") 
    */
    public function home(  ProduitRepository $produitRepository    )
    {
     // on injecte en dépendance le repository de Produit afin de pouvoir récupérer les données de la table produit en BDD
     // Les repository sont systématiquement appelé pour effectuer les requêtes de SELECT en BDD
     // On utilise sa méthode findAll() pour récupérer toutes les données ( equivaut à SELECT * FROM produit)
     $produits=$produitRepository->findAll();
        
     
     return $this->render("home/home.html.twig",[

        'produits'=>$produits

     ] ) ;
    }

    /**
    *@Route("/ajoutPanier/{id}/{param}", name="ajoutPanier") 
    */
    public function ajoutPanier(PanierService $panier, $id, $param )
    {
     $panier->ajout($id);

     //dd($panier->detailPanier());

     $this->addFlash('success', 'Ajouté au panier !!!');
     if ($param=='home') {
         return $this->redirectToRoute("home",[] ) ;
     } else {
        return $this->redirectToRoute("panier",[] ) ;
     }
    
    }

    /**
    *@Route("/retraitPanier/{id}", name="retraitPanier") 
    */
    public function retraitPanier(PanierService $panier, $id)
    {
     $panier->retrait($id);

     
     return $this->redirectToRoute("panier",[] ) ;
    }

        /**
    *@Route("/supprimerPanier/{id}", name="supprimerPanier") 
    */
    public function supprimerPanier(PanierService $panier, $id)
    {
     $panier->supprimer($id);

     
     return $this->redirectToRoute("panier",[] ) ;
    }

        /**
    *@Route("/viderPanier", name="viderPanier") 
    */
    public function viderPanier(PanierService $panier)
    {
     $panier->vider();

    
     return $this->redirectToRoute("home",[] ) ;
    }

    /**
    *@Route("/panier", name="panier") 
    */
    public function panier(PanierService $panierService)
    {
     
        $panier=$panierService->detailPanier();
     
     return $this->render("home/panier.html.twig",[
        'panier'=>$panier
     ] ) ;
    }


    /**
    *@Route("/detailProduit/{id}", name="detailProduit") 
    */
    public function detailProduit(Produit $produit, Request $request, EntityManagerInterface $manager )
    {

        if(!empty($_POST))
        {
            $comment= new Commentaire();
            $comment->setNote($request->request->get('note'));
            $comment->setCommentaire($request->request->get('commentaire'));
            $comment->setProduit($produit);
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('success', 'Merci pour votre  contribution');
            return $this->redirectToRoute('home');

        }
     
     
     return $this->render("home/detail.html.twig",[
        'produit'=>$produit
     ] ) ;
    }

    /**
    *@Route("/commande", name="commande") 
    *@IsGranted("ROLE_USER")
    */
    public function commande( PanierService $panierService, EntityManagerInterface $manager   )
    {

        $commande = new Commande();
        $commande->setDate(new DateTime());

        $commande->setRef('Ref5000');
        $commande->setUtilisateur($this->getUser());
        $panier=$panierService->detailPanier();

        foreach ($panier as $item) {
           
            $achat=new Achat();
            $achat->setCommande($commande);
            $achat->setProduit($item['produit']);
            $item['produit']->setStock($item['produit']->getStock()-$item['quantite']);
            $manager->persist($item['produit']);
            $achat->setQuantite($item['quantite']);
            $manager->persist($achat);
                 $panierService->supprimer($item['produit']->getId());

        }

        $manager->persist($commande);
        $manager->flush();
        $this->addFlash('success', 'Merci pour votre confiance, accédez au détail de votre commande dans votre espace  privé');

     
     
     return $this->redirectToRoute("home",[] ) ;
    }









}// fermeture du controller
