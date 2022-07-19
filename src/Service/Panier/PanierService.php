<?php

namespace App\Service\Panier;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{


    public $session;

    public $repository;


    public function __construct(SessionInterface $session, ProduitRepository $repository)
    {
        $this->session = $session;
        $this->repository = $repository;
    }

    public function ajout(int  $id)
    {
        // on déclare une entrée panier en session qui sera par défaut un tableau vide 
        $panier = $this->session->get('panier', []);

        // on verifi dans le tableau en session si il y a un indice existant sur cet id
        // si non, on initialise la quantité à un, sinon on incrémente de 1 la quantite de fois qu'il a été ajouté 
        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id]++;
        }

        //  on met à jour la session avec $panier qui contient la session avec la modification de quantité
        $this->session->set('panier', $panier);
    }


    public function retrait(int $id)
    {

        $panier = $this->session->get('panier', []);

        // si il y a deja un produit de cet id en session et que sa quantité est différente de 1 ( superieur à 1) on décrémente la quantité sinon on supprime l'entrée en session
        if (!empty($panier[$id]) && $panier[$id] !== 1) {
            $panier[$id]--;
        } else {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }


    public function supprimer(int $id)
    {

        $panier = $this->session->get('panier', []);


        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }


    public function vider()
    {
        // equivalent d'un session_destroy pour supprimer une entrée totale en session
        $this->session->remove('panier');
    }
    
    public function detailPanier()
    {

        $panier = $this->session->get('panier', []);

        $detail=[];

        foreach($panier as $id=>$quantite){
            $detail[]=[
                'produit'=>$this->repository->find($id),
                'quantite'=>$quantite
            ];

        }

        return $detail;

    }

    public function total()
    {

        $total=0;
        $panier=$this->detailPanier();
        foreach($panier as $index){

           $total += $index['produit']->getPrix()*$index['quantite'];

        }

        return $total;

    }


}
