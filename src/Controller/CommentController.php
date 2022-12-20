<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends MyController
{
    public function __construct(private UserRepository $userRepository, private PostRepository $postRepository, private CommentRepository $commentRepository)
    {


        parent::__construct($this->userRepository);
    }

    #[Route('/comment/edit/{comment<\d+>}', name: 'app_comment_edit')]
    public function edit(Comment $comment, Request $request): Response
    {

        $commentForm = $this->createForm(CommentFormType::class, $comment);
//        TODO has problem
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            /**@var $formComment Comment */

            if ($this->isAllowThisUserToDelete($comment->getUser()?->getId())) {
                $formComment = $commentForm->getData();
                $this->commentRepository->save($formComment, true);
            }


            return $this->redirectToRoute('app_post_detail', ['post' => $comment->getPost()?->getId()]);

        }
        return $this->render('comment/edit.html.twig', [
            'commentForm' => $commentForm
        ]);
    }


    #[Route('/comment/del/{comment<\d+>}', name: 'app_comment_delete')]
    public function delete(Comment $comment, Request $request): Response
    {

        $postId = $comment->getPost()?->getId();
        if ($this->isAllowThisUserToDelete($comment->getUser()?->getId()))
            $this->commentRepository->remove($comment, true);

        return $this->redirectToRoute('app_post_detail', ['post' => $postId]);
    }
}
