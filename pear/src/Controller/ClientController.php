<?php
// src/Controller/ClientController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Lender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Repository\UserRepository;
use App\Controller\ArrayList;



class ClientController extends AbstractController
{ 
  public function add_client(Request $request)
  {
    // On crée un objet User
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
      ->add('naissance', DateType::class)
      ->add('save',      SubmitType::class)
    ;

    $form = $formBuilder->getForm();


     $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $user = $form->getData();
        
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('list_clients');
    }


    // À partir du formBuilder, on génère le formulaire
    

    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule

    return $this->render('app/add_user.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function list_clients( UserRepository $userRepository)
  {
    $listUser = $userRepository -> findAll();

    foreach ($listUser as $user){
       $user -> getNom();
       $user -> getPrenom();
       $user -> getEmail();
      //echo $user -> getNaissance().toString();

    }
    return $this -> render ('app/list_clients.html.twig', 
    array("listUser" => $listUser));
  }

  public function delete_client(Request $request, UserRepository $userRepository)
  {
   
    $user = new User();

    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class);

    $formBuilder      ->add('id', IntegerType::class)
                      ->add('save', SubmitType::class);

    $form = $formBuilder -> getForm();
    
    $form->handleRequest($request);
 
    if ($form->isSubmitted() && $form->isValid()) {
      $id = $form -> getdata();
     $user = $userRepository -> find($id);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($user);
      $entityManager->flush();

      $listUser = $userRepository -> findAll();
      return $this -> render ('app/list_clients.html.twig', array("listUser" => $listUser));
    }

    return $this->render('app/delete_user.html.twig', array(
      'form' => $form->createView(),
    ));
  } 
  
}