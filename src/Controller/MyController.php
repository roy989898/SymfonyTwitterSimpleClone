<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class MyController extends AbstractController
{


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

}
