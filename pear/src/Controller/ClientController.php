<?php
// src/Controller/ClientController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Lender;
use App\Controller\ArrayList;
use App\Repository\BorrowingRepository;
use App\Repository\LenderRepository;
use Doctrine\DBAL\Types\JsonType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Test\FormBuilderInterface;

class ClientController extends AbstractController
{ 

  /* public function add_client(FormBuilderInterface $builder, array $options ){


    $builder
      ->add('nom'    )
      ->add('prenom')   
      ->add('email')      
      ->add('password', PasswordType::class)
      ->add('naissance', DateType::class)
      ;
     
      
  }}*/

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
      ->add('naissance', BirthdayType::class)
      ->add('save',      SubmitType::class)
      ->add('roles', CollectionType::class, [
        'entry_type'   => ChoiceType::class,
        'entry_options'  => [
            'choices'  => [
              $user->getRolesNames()
            ],
        ],
    ]);
      


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

    return $this->render('user/add_user.html.twig', array(
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
       $user -> getNaissance();
     
    }
    return $this -> render ('user/list_users.html.twig', 
    array("listUser" => $listUser));
  }


  public function delete_client(UserRepository $userRepository, BorrowingRepository $borrowingRepository, $id)
  {
    
      $user = $userRepository -> findOneById($id);
      $borrowing = $borrowingRepository -> findOneByidUser($id);

      $entityManager = $this->getDoctrine()->getManager();
      if(!is_null($borrowing)) {$entityManager->remove($borrowing);}
      
      $entityManager->remove($user);
      $entityManager->flush();

      $listUser = $userRepository -> findAll();
      return $this -> render ('user/list_users.html.twig', array("listUser" => $listUser));
    }

    

    public function add_lender()
    {
      $entityManager = $this->getDoctrine()->getManager();
      $connUser = $this->getUser();
      $role[] = 'ROLE_LENDER';
      $connUser->setRoles($role);
      $entityManager->flush();
      return $this->redirectToRoute('list_lenders');
      
  }

  public function list_lenders(UserRepository $userRepository)
  {

    $listLender = $userRepository -> findAllLenders('ROLE_LENDER');

    return $this -> render ('lender/list_lenders.html.twig', array("listLender" => $listLender));
  }

    
  
}