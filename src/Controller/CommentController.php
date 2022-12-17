<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/edit', name: 'app_comment_edit')]
    public function edit(): Response
    {
        return $this->render('comment/edit.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }
}
