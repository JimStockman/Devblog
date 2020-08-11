<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/post", name="post.")
 * @package App\Controller
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param  PostRepository  $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();
        
        dump($posts);
        
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts
        ]);
    }
    
    /**
     * @Route("/{id}", name="show")
     * @param  Post  $post
     * @return Response
     */
    public function show(Post $post)
    {
        
        dump($post);
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }
    
    
    /**
     * @Route("/create", name="create")
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        // We create a new Post object and assign a title to it
        $post = new Post();
        $post->setTitle('This is a static title');
        
        // Entity manager persists the post in the db
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        
        // Sends all the queries to the db
        $em->flush();
        
        return new Response('Post was created');
        
//        return $this->render('post/create.html.twig', [
//
//        ]);
    }
    
    /**
     * @Route("/destroy/{id}", name="destroy")
     * @param  Post  $post
     */
    public function destroy(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        
        return $this->redirect($this->generateUrl('post.index'));
    }
}
