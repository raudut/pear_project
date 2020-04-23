<?php
// src/Controller/AppController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\BorrowingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductController extends AbstractController
{
  


  public function add_product(Request $request){
    // On crée un objet Advert
    $product = new Product();
    
    $entityManager = $this->getDoctrine()->getManager();


    $entityManager = $this->getDoctrine()->getManager();

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
      ->add('statut', CollectionType::class, [
        'entry_type'   => ChoiceType::class,
        'entry_options'  => [
            'choices'  => [
              $product->getStatutNames()
            ],
        ],
    ])
    ;
    // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard

    // À partir du formBuilder, on génère le formulaire
    $form = $formBuilder->getForm();

    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
      $task = $form->getData();
      $entityManager -> persist($product);
      $entityManager->flush();

      return $this->redirectToRoute('list_products');
    }

    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule

    return $this->render('product/add_product.html.twig', array(
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
    return $this -> render ('product/list_products.html.twig', array("listProduct" => $listProduct));
  }
  


  public function list_products( ProductRepository $productRepository)
  {

    $listProducts = $productRepository -> findAll();

      foreach($listProducts as $product)
      {
        
        $product -> getNom();
        $product -> getPrix();
        $product -> getCaution();
        $product -> getEtat();
        $product -> getEmplacement();
        $product -> getNumSerie();
        $product -> getKit();
      }
       return $this  -> render('product/list_products.html.twig',
        array("Liste"=> $listProducts));
  }

  
    
  public function delete_products(ProductRepository $productRepository, BorrowingRepository $borrowingRepository, $id)
  {
   
    $product = $productRepository -> findOneById($id);
    $borrowing = $borrowingRepository -> findOneByidUser($id);

      $entityManager = $this->getDoctrine()->getManager();
      if(!is_null($borrowing)) {$entityManager->remove($borrowing);}
      $entityManager->remove($product);
      $entityManager->flush();

      $listProducts = $productRepository -> findAll();
      return $this -> render ('product/list_products.html.twig', array("Liste" => $listProducts));
  }

}