<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
//    /**
//     * @Route("/registration", name="registration")
//     */
//    public function index()
//    {
//        return $this->render('registration/index.html.twig', [
//            'controller_name' => 'RegistrationController',
//        ]);
//    }
    
    /**
     * @Route("/register", name="register")
     * @param  Request  $request
     * @param  UserPasswordEncoderInterface  $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
    
            dump([
                'form' => $form,
                'user' => $user
            ]); die;

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('index'));
        }

        return $this->render('registration/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
