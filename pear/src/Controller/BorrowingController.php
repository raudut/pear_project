<?php

namespace App\Controller;

use App\Entity\Borrowing;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Repository\ProductRepository;
use App\Repository\BorrowingRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class BorrowingController extends AbstractController
{
    
    public function add_borrowing(Request $request)
    {

        // On crée un objet Borrowing
    $borrowing = new Borrowing();

    $entityManager = $this->getDoctrine()->getManager();

    // On crée le FormBuilder grâce au service form factory
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $borrowing);


    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $formBuilder
      ->add('dateDebut', DateType::class)
      ->add('dateFin', DateType::class)
      ->add('save',      SubmitType::class)
      ->add('idProduct', EntityType::class, [
                'class' => Product::class,
                'placeholder' => '== Choisir un objet ==',
            ]) ;
    
      
    $form = $formBuilder->getForm();


     $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $borrowing = $form->getData();
        $borrowing->setIdUser($this->getUser());
        $entityManager->persist($borrowing);
        $entityManager->flush();

        return $this->redirectToRoute('list_borrowings');
    }


    // À partir du formBuilder, on génère le formulaire
    

    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule

    return $this->render('borrowing/add_borrowing.html.twig', array(
      'form' => $form->createView(),
    ));
    }



    public function list_borrowings( BorrowingRepository $borrowingRepository)
  {
    $listBorrowing = $borrowingRepository -> findAll();
    foreach ($listBorrowing as $bo){
       $bo -> getIdUser();
       $bo -> getIdProduct();
       $bo -> getDateDebut();
       $bo -> getDateFin();
    }
    return $this -> render ('borrowing/list_borrowings.html.twig', 
    array("listBorrowing" => $listBorrowing));
  }
}
