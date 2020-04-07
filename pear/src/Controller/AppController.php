<?php
// src/Controller/AppController.php

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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;



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

  public function list_obj()
  {
  	return new Response(" liste des objets à préter");
  }

  public function add_lender(Request $request)
  {
    // On crée un objet Advert
    $lender = new Lender();

    // On crée le FormBuilder grâce au service form factory
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $lender);

    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $formBuilder
      ->add('idlender',      TextType::class)
      ->add('iduser',     TextType::class)
      ->add('save',      SubmitType::class)
    ;
    // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard

    // À partir du formBuilder, on génère le formulaire
    $form = $formBuilder->getForm();

    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule

    return $this->render('app/add_lender.html.twig', array(
      'form' => $form->createView(),
    ));
  }
}