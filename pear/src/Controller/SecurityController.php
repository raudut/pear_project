<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrtionType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


 class SecurityController extends AbstractController
 {

//     public function registration(Request $request, ObjectManager $manager)
//     {
//      $user = new User();

//      $form = $this->createForm(RegistrtionType::class, $user);

//      $form->handleRequest($request);

//      if ($form->isSubmitted() && $form->isValid()) {
//          $manager->persist($user);
//          $manager->flush();


        
//      }

//         return $this->render('user/add_user.html.twig', [
//             'form' => $form->createView()
//         ]);
//     }
    
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
     //    if ($this->getUser()) {
     //        return $this->redirectToRoute('target_path');
     //    }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
