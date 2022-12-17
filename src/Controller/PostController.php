<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PostController extends AbstractController
{

    public function __construct(private PostRepository $postRepository)
    {
    }

    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        $posts = $this->postRepository->findAllWithUser();
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[isGranted('ROLE_USER')]
    #[Route('/post/add', name: 'app_post_add')]
    public function addPost(Request $request)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var $formPost Post */
            $formPost = $form->getData();
//             link to the user
            $user = $this->getUser();
            $formPost->setUser($user);
            $this->postRepository->save($formPost, true);

        }

        return $this->render('post/create.html.twig', ['form' => $form]);

    }


    #[isGranted('ROLE_USER')]
    #[Route('/post/edit/{post<\d+>}', name: 'app_post_edit')]
    public function editPost(Post $post, Request $request): Response
    {
        $postUserID = $post->getUser()?->getId();
        $userID = $this->getUser()?->getId();
        if ($postUserID !== $userID) {
            $this->redirectToRoute('app_post');
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var $formPost Post */
            $formPost = $form->getData();

            $this->postRepository->save($formPost, true);

        }

        return $this->render('post/edit.html.twig', ['form' => $form]);

    }


}
