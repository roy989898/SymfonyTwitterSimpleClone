<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class MyController extends AbstractController
{


    public function __construct(private UserRepository $userRepository)
    {
    }

    public function isAllowThisUserToDelete(?int $objectUserID): bool
    {
        $userID = $this->getUser()?->getId();
        return $objectUserID !== null && ($objectUserID === $userID || $this->isGranted('ROLE_SUPER_ADMIN'));
    }

    protected function getMyUser(): ?User
    {

        /**@var ?User $user */
        $user = $this->getUser();

        return $user;

    }


    protected function likeThePost(Post $post)
    {

        $user = $this->getMyUser();

        $user?->addLikedPost($post);

        if ($user !== null) {
            $this->userRepository->save($user, true);
        }


    }

    protected function unLikeThePost(Post $post)
    {

        $user = $this->getMyUser();

        $user?->removeLikedPost($post);

        if ($user !== null) {
            $this->userRepository->save($user, true);
        }


    }

    protected function likeTheComment(Comment $comment)
    {

        $user = $this->getMyUser();

        $user?->addLikedComment($comment);

        if ($user !== null) {
            $this->userRepository->save($user, true);
        }


    }


    protected function unLikeTheComment(Comment $comment)
    {

        $user = $this->getMyUser();

        $user?->removeLikedComment($comment);

        if ($user !== null) {
            $this->userRepository->save($user, true);
        }


    }


}
