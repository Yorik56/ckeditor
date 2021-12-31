<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'produit')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $produits = $doctrine->getRepository(Produit::class)->findAll();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produits' => $produits
        ]);
    }

    #[Route('/produit_creation', name: 'creation_produit')]
    public function createProduit(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $produit = new Produit();
        $produit->setPrix(1999);
        $produit->setDescription('Ergonomic and stylish!');
        $entityManager->persist($produit);
        $entityManager->flush();

        return new Response('Saved new produit with id '.$produit->getId());
    }

    #[Route('/produit_edition_note', name: 'edition_note')]
    public function editNoteProduit(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $produit = $doctrine->getRepository(Produit::class)->find($_POST['id_produit']);
        $produit->setNote($_POST['note']);
        // tell Doctrine you want to (eventually) save the Produit (no queries yet)
        $entityManager->persist($produit);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new produit with id '.$produit->getId());
    }
}
