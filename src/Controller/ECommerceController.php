<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Contact;
use App\Entity\Produit;
use App\Form\ContactType;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\html;

class ECommerceController extends AbstractController
{
    #[Route('/e_commerce', name: 'app_e_commerce')]
    public function index(): Response
    {
        return $this->render('e_commerce/index.html.twig');
    
    }

    #[Route('/home', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('e_commerce/home.html.twig');
    }

    #[Route('/profil', name: 'app_profil')]
    public function profil( EntityManagerInterface $manager,ProduitRepository $repo): Response
    {
       
        if($this->getUser())  //si l'utilisateur est connecté
        {
            $colonnes= $manager->getClassMetadata(Produit::class)->getFieldNames();

            $produit = $repo->getProduitByUser($this->getUser()); // on récupère toute les données de l'utilisateur qui est connecté
            if(!$produit)
            {
                $this->addFlash('info',"Vous n'avez pas de produits");
            }
            return $this->render("e_commerce/profil.html.twig", array(
                'produits' => $produit,
                'colonnes' => $colonnes
            ));
        }
        else 
        {
            return $this->redirectToRoute('app_e_commerce'); // bien mettre la route
        }

    }

   
    #[Route('/new', name: 'app_new')]
    #[Route('/edit', name: 'app_edit')]

      public function form(Request $request, EntityManagerInterface $manager, Produit $produit = null)
      {
          if(!$produit)
          {
              $produit = new Produit;
              $produit->setAuteur($this->getUser());

          }
  
          $form = $this->createForm(ProduitType::class, $produit);
          $form->handleRequest($request);
  
          if($form->isSubmitted() && $form->isValid())
          {
              $manager->persist($produit);
              $manager->flush();
  
              return $this->redirectToRoute('app_profil', array(
                  'id' => $produit->getId()
              ));
          }
  
          return $this->render("e_commerce/form.html.twig", array(
              'formProduit' => $form->createView(),
              'editMode' => $produit->getId() !== NULL
          ));
      }

      #[Route('/show/{id}', name:'app_show')]
    public function show(Produit $produit, ProduitRepository $repo, Request $request, EntityManagerInterface $manager, Commentaire $commentaire = null)
    {
        return $this->render("e_commerce/show.html.twig", [
            'produit' => $produit
        ]);

        
    }
    
  


    


}
