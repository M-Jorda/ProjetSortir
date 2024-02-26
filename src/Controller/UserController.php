<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController {
    #[Route('/my_profile', name: 'user_profile')]
    public function myProfile() {
        return $this->render('user/profile.html.twig');
    }
}