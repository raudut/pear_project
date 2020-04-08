<?php
// src/Controller/AppController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use App\Repository\ProductRepository;


class AppController extends AbstractController
{
  public function home()
  {
  	return $this -> render('app/home.html.twig');
  }

  public function add_user(Request $request)
  {
    // On crée un objet Advert
    $user = new User();

    $entityManager = $this->getDoctrine()->getManager();

    // On crée le FormBuilder grâce au service form factory
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);

    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $formBuilder
      ->add('nom',      TextType::class)
      ->add('prenom',     TextType::class)
      ->add('email',   EmailType::class)
      ->add('password',    PasswordType::class)
      ->add('save',      SubmitType::class)
    ;
    // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard

    // À partir du formBuilder, on génère le formulaire
    $form = $formBuilder->getForm();

    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule

    return $this->render('app/add_user.html.twig', array(
      'form' => $form->createView(),
    ));
  }

 

  public function add_product(Request $request){
    // On crée un objet Advert
    $product = new Product();
    

    // On crée le FormBuilder grâce au service form factory
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $product);

    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $formBuilder
      ->add('nom',      TextType::class)
      ->add('prix',     TextType::class)
      ->add('caution',   TextType::class)
      ->add('etat',    TextType::class)
      ->add('emplacement',    TextType::class)
      ->add('num_serie',    TextType::class)
      ->add('kit',    TextType::class)
      ->add('save',      SubmitType::class)
    ;
    // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard

    // À partir du formBuilder, on génère le formulaire
    $form = $formBuilder->getForm();

    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
      $task = $form->getData();

      $entityManager -> persist($product);
      $entityManager->flush();

      return $this->redirectToRouge('home');
    }

    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule

    return $this->render('app/add_product.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function list_obj( ProductRepository $productRepository)
  {

    $listProduct = $productRepository -> findAll();

    foreach ($listProduct as $product){
       $product -> getNom();
       $product -> getPrix();
       $product -> getCaution();
       $product -> getEtat();
       $product -> getEmplacement();
       $product -> getNumSerie();
       $product -> getKit();

    }
    return $this -> render ('app/list_products.html.twig', array("listProduct" => $listProduct));
  }
  



}