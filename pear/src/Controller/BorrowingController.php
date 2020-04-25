<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Borrowing;
use Doctrine\DBAL\Types\ArrayType;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Repository\BorrowingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BorrowingController extends AbstractController
{
    
    public function add_borrowing(Request $request, ProductRepository $productRepository)
    {

        // On crée un objet Borrowing
    $borrowing = new Borrowing();

    $entityManager = $this->getDoctrine()->getManager();

    // On crée le FormBuilder grâce au service form factory
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $borrowing);

    //$products = $productRepository -> findProductByStatut('STATUT_DISPONIBLE');

    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $formBuilder
      ->add('dateDebut', DateType::class)
      ->add('dateFin', DateType::class)
      ->add('save',      SubmitType::class)
      ->add('idProduct', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'nom',
                'placeholder' => '== Choisir un objet ==',
                /*'query_builder'=> function(ProductRepository $repo){
                  return $repo->getDispoProduct();
                }*/
                'choices' => $productRepository -> findProductByStatut('STATUT_DISPONIBLE')
            ]) 
      /*->add('idProduct', ChoiceType::class, array(
        'placeholder' => '== Choisir un objet ==',
        'choices' => $products
    ))*/
      ;
    
      
    $form = $formBuilder->getForm();
    


     $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $borrowing = $form->getData();
        $borrowing->setIdUser($this->getUser());
        $entityManager->persist($borrowing);
        $entityManager->flush();

        $prod = $borrowing->getIdProduct();
        $statut[] = 'STATUT_LOUE';
        $prod->setStatut($statut);
        $entityManager->flush();

        
        return $this->redirectToRoute('list_borrowings');
        echo($borrowing->GetId());
    }
    
    echo($this->get('security.token_storage')->getToken()->getUser()->getId());
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

  public function delete_borrowing(BorrowingRepository $borrowingRepository , $id)
  {
    
      $bo = $borrowingRepository -> findOneById($id);

      $entityManager = $this->getDoctrine()->getManager();
      
      $entityManager->remove($bo);
      $entityManager->flush();

      $listBorrowing = $borrowingRepository -> findAll();
      return $this -> render ('borrowing/list_borrowings.html.twig', array("listBorrowing" => $listBorrowing));
    }
}
