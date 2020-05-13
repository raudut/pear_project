<?php
// src/Controller/ClientController.php

namespace App\Controller;

//use App\Form\UserFormType;
use App\Entity\User;
use App\Entity\Lender;
use App\Controller\ArrayList;
use App\Repository\BorrowingRepository;
use App\Repository\LenderRepository;
use App\Repository\ProductRepository;
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
    
 public function add_client(Request $request)
  {
    // On crée un objet User
    $user = new User();
    $mailadmin = new AppController();
    $mailuser = new AppController();

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
        $userlastname = $user->getNom();
        $userfirstname = $user->getPrenom();
        $username = " $userfirstname $userlastname";
        $usermail = $user->getEmail();
        $mailadmin-> send_email_add_user_admin($username,  $usermail);
        $mailuser -> send_email_add_user_confirmation($username,  $usermail);
        $entityManager->persist($user);
        $entityManager->flush();
        $body = [
          'Messages' => [
              [
              'From' => [
                  'Email' => "pear@epf.fr",
                  'Name' => "PEAR"
              ],
              'To' => [
                  [
                  'Email' => "marthe.franckdepreaumont@epfedu.fr",
                  'Name' => "MOUA"
                  ]
              ],
              'Subject' => "Greetings from Mailjet.",
              'HTMLPart' => "<h3>Dear User, welcome to Mailjet!</h3><br />May the delivery force be with you!"
              ]
          ]
      ];
       
      $ch = curl_init();
       
      curl_setopt($ch, CURLOPT_URL, "https://api.mailjet.com/v3.1/send");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
          'Content-Type: application/json')
      );
      curl_setopt($ch, CURLOPT_USERPWD, "47219a1c999266c91efd07942860e61d:46478c82213deacd3a251becee8e5776");
      $server_output = curl_exec($ch);
      curl_close ($ch);
       
      $response = json_decode($server_output);
      if ($response->Messages[0]->Status == 'success') {
          echo "Email sent successfully.";
      }
        return $this->redirectToRoute('login');
    }


    // À partir du formBuilder, on génère le formulaire
    

    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule

    return $this->render('user/add_user.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function show_user(UserRepository $userRepo, $id, ProductRepository $productRepository, BorrowingRepository $borrowingRepo){
    $client= $userRepo -> findOneById($id);
    $listProduct =  $productRepository -> findBy(['owner' => $id]);
    $listBorrow = $borrowingRepo -> findBy(['idUser' => $id]);
    $listBProduct= array();
    foreach($listBorrow as $borrow)
    {
      $idProduct = $borrow -> getIdProduct();
      $product = $productRepository -> findby(['id' => $idProduct]);
      $listBorrow = array($product);
    }
    return $this-> render('user/show_user.html.twig', array(
      'client'=>$client,
      'listLendings'=> $listProduct, 
      'listBorrowings' => $listBorrow,
      'listBProduct' => $listBProduct
    ));
  }

/*
  public function edit_client(Request $request, User $user){
    
      $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
 
            $this->addFlash(
            'notice',
            'Le slide a correctement été modifier.'
            );
 
            return $this->redirectToRoute('list_clients');
        }

        return $this->render('user/edit_user.html.twig', [
            'slide' => $slide,
            'form' => $form->createView(),
        ]);

  }

*/


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
      return $this->redirectToRoute('home_lender');
      
  }

  public function list_lenders(UserRepository $userRepository)
  {

    $listLender = $userRepository -> findAllLenders('ROLE_LENDER');

    return $this -> render ('lender/list_lenders.html.twig', array("listLender" => $listLender));
  }

    
  
}