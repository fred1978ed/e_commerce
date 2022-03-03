<?php

namespace App\Controller;

use PhpParser\Comment;
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
    #[Route('/e/commerce', name: 'app_e_commerce')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    
    }

    #[Route('/home', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('e_commerce/home.html.twig');
    }

    #[Route('/profil', name: 'app_profil')]
    public function profil(Request $request, EntityManagerInterface $manager,ProduitRepository $repo): Response
    {
        $produit = new Produit;
         $form = $this->createForm(ProduitType::class, $produit);
         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid())
         {
            $manager->persist($produit);
            $manager->flush();
         }


        $produit = $repo->findAll();
        return $this->render('e_commerce/profil.html.twig', [
            'produit' => $produit
        ]);

        return $this->render('e_commerce/profil.html.twig');
    }

    // #[Route('/show/{id}', name: 'app_show')]
    // public function show(Produit $produit,Request $request,EntityManagerInterface $manager)
    // {
    //     $produit = new Produit();

    //     if($form->isSubmitted() && $form->isValid())
    //     {
    //         $manager->persist($produit);
    //         $manager->flush();

    //         return $this->redirectToRoute('show', array(
    //             'id' => $produit->getId()
    //         ));
    //     }

    //     return $this->render("e_commerce/show.html.twig", array(
    //         'produit' => $produit,
    //         'formProduit' => $form->createView()
    //     ));
    // }


    


}
