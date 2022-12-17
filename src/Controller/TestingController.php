<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TestingController extends AbstractController
{

    public function __construct(private Security $security)
    {
    }

    #[Route('/testing', name: 'app_testing')]
    public function index(): Response
    {
        return $this->render('testing/index.html.twig', [
            'controller_name' => 'TestingController',
        ]);
    }


    #[isGranted('ROLE_USER')]
    #[Route('/testingProtected', name: 'app_testing')]
    public function testingProtected(): Response
    {

        return $this->render('testing/protected.html.twig', [

        ]);
    }
}
