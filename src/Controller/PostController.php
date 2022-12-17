<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentFormType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PostController extends AbstractController
{

    public function __construct(private PostRepository $postRepository, private CommentRepository $commentRepository)
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

    #[Route('/post/{post<\d+>}', name: 'app_post_detail')]
    public function postDetail(Post $post, Request $request)
    {

        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            /**@var $formComment Comment */
            $formComment = $commentForm->getData();
            $user = $this->getUser();
            $formComment->setUser($user);
            $formComment->setPost($post);
            $this->commentRepository->save($formComment, true);

//            TODO redirect to where???
            return $this->redirectToRoute('app_post_detail', ['post' => $post->getId()]);
        }


        return $this->render('post/detail.html.twig', [
            'post' => $post,
            'commentForm' => $commentForm
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

            $this->addFlash('success', 'Added Post');
            return $this->redirectToRoute('app_post');

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
            $this->addFlash('success', 'Updated Post');
            return $this->redirectToRoute('app_post');
        }

        return $this->render('post/edit.html.twig', ['form' => $form]);

    }


    #[isGranted('ROLE_USER')]
    #[Route('/post/del/{post<\d+>}', name: 'app_post_delete')]
    public function deletePost(Post $post, Request $request)
    {
        $postUserID = $post->getUser()?->getId();
        $userID = $this->getUser()?->getId();
        if ($postUserID !== $userID) {
            $this->redirectToRoute('app_post');
        }

        $this->postRepository->remove($post, true);
        $this->addFlash('success', 'Deleted Post');

        return $this->redirectToRoute('app_post');


    }


}
