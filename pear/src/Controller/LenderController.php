<?php

namespace App\Controller;
use App\Entity\Lender;
use App\Repository\UserRepository;
use App\Repository\LenderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LenderController extends AbstractController
{
    /**
     * @Route("/lender", name="lender")
     */
    public function index()
    {
        return $this->render('lender/index.html.twig', [
            'controller_name' => 'LenderController',
        ]);
    }
    
    public function add_lender(Request $request)
    {
      $lender = new Lender();
      $entityManager = $this->getDoctrine()->getManager();
  
      // On crée le FormBuilder grâce au service form factory
      $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $lender);
  
      // On ajoute les champs de l'entité que l'on veut à notre formulaire
      $formBuilder
        ->add('iduser',  IntegerType::class)
        ->add('save',  SubmitType::class)
        ;

        $form = $formBuilder->getForm();


        $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
           $task = $form->getData();
           
           $entityManager->persist($lender);
           $entityManager->flush();
   
           return $this->redirectToRoute('list_lenders');
       }
       
       return $this->render('lender/add_lender.html.twig', array(
        'form' => $form->createView(),
      ));
  }

  public function list_lenders( LenderRepository $lenderRepository)
  {

    $listLender = $lenderRepository -> findAll();

    foreach ($listLender as $lender){
       $lender -> getIduser();
        }
  	return $this -> render ('lender/list_lenders.html.twig', array("listLender" => $listLender));
  }
}
