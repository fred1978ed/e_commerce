<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\AdminProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/produit', name: 'admin_produit')]
    public function adminProduit(ProduitRepository $repo, EntityManagerInterface $manager){
 // On appel getManager afin de récupérer le noms des champs et des colonnes
    
        // récupération des champs
    $colonnes = $manager->getClassMetadata(Produit::class)->getFieldNames();
        dump($colonnes);
    $produit = $repo->findAll();
        dump($produit);
            return $this->render('admin/produit.html.twig', [
                'Produits' => $produit,
                'colonnes' => $colonnes
            ]);
        }

        #[Route('/admin/{id}/delete-produit', name: 'admin_delete_produit')]
        public function deleteProduit(Produit $produit, EntityManagerInterface $manager)
        {
            $manager->remove($produit);
            $manager->flush();

            $this->addFlash('success', "Le produit a bien été supprimé !");

            return $this->redirectToRoute('admin_produit');
        }
    
                
        
         #[Route('/admin/produit/new', name: 'admin_new_produit')]
         #[Route('/admin/{id}/edit-produit', name: 'admin_edit_produit')]
     public function editProduit(Produit $produit = null, Request $request, EntityManagerInterface $manager)
             {
                 dump($produit);
             if(!$produit)
                {
                     $produit = new Produit;
                 }
             $form = $this->createForm(AdminProduitType::class, $produit);
             $form->handleRequest($request);
                 if($form->isSubmitted() && $form->isValid())
                 {
                 $manager->persist($produit);
                 $manager->flush();
             $this->addFlash('success', 'Les modifications ont bien été enregistrés !');
      return $this->redirectToRoute('admin_produit');
                 }
      return $this->render('admin/adminform.html.twig', [
             'formAdminProduit' => $form->createView(),
             'editMode' => $produit->getId() !== null
         ]);
         }




}
