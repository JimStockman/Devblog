<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    
    /**
     * @Route("/custom/{username?}", name="custom")
     * @param  Request  $request
     * @return Response
     */
    public function custom(Request $request)
    {
        dump($request);
        
        return $this->render('main/custom.html.twig', [
            'username' => $request->get('username'),
        ]);
    }
}
