<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class ParticipantController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user_participant', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function participant(User $participant, UserRepository $userRepository): Response
    {

        return $this->render('user/participant.html.twig', [
                'participant' => $participant
    ]);
    }
}