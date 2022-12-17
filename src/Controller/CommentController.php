<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    public function __construct(private CommentRepository $commentRepository, private PostRepository $postRepository)
    {
    }

    #[Route('/comment/edit/{comment<\d+>}', name: 'app_comment_edit')]
    public function edit(Comment $comment, Request $request): Response
    {

        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid() && $comment->getUser()?->getId() !== null && ($this->getUser()?->getID() === $comment->getUser()?->getId())) {
            /**@var $formComment Comment */
            $formComment = $commentForm->getData();
            $this->commentRepository->save($formComment, true);

            return $this->redirectToRoute('app_post_detail', ['post' => $comment->getPost()?->getId()]);

//            TODO redirect to where???
        }
        return $this->render('comment/edit.html.twig', [
            'commentForm' => $commentForm
        ]);
    }
}
