<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @Route("/show/{id}", name="show")
     * @param  int  $id
     * @param  PostRepository  $postRepository
     * @return Response
     */
    public function show(int $id, PostRepository $postRepository)
    {
        $post = $postRepository->findPostWithCategory($id);
        
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
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);
        
        // Will only happen on POST requests
        if ($form->isSubmitted()) {
            
             //Entity manager persists the post in the db
            $em = $this->getDoctrine()->getManager();
            
            
            // We check if a file was attached to the request
            // If so
            /** @var UploadedFile $file */
            dump($request);
            
            $file = $request->files->get('post')['attachment'];
            if ($file) {
               $fileName = md5(uniqid()) . '.' . $file->guessClientExtension();
               
               $file->move(
                   $this->getParameter('uploads_dir'),
                   $fileName
               );
               
               $post->setImage($fileName);
            }
            
            $em->persist($post);

            // Sends all the queries to the db
            $em->flush();
    
            $this->addFlash('create', 'Post created successfully!');
    
            return $this->redirect($this->generateUrl('post.index'));
        }
        
//        // We create a new Post object and assign a title to it
//        $post->setTitle('This is a static title');
//
//
        
        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
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
        
        $this->addFlash('delete', 'Post removed successfully!');
        
        return $this->redirect($this->generateUrl('post.index'));
    }
}
