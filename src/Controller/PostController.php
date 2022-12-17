<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    public function __construct(private PostRepository $postRepository)
    {
    }

    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/post/add', name: 'app_post_add')]
    public function addPost(Request $request)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var $formPost Post */
            $formPost = $form->getData();
            $this->postRepository->save($formPost, true);

        }

//        TODO add the the form
        return $this->render('post/create.html.twig');

    }
}
