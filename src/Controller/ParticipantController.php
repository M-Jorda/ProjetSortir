<?php

namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class ParticipantController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user_participant', methods: ['GET'])]
    public function participant(int $id, UserRepository $userRepository): Response
    {

        $participant = $userRepository->find($id);

        return $this->render('user/participant.html.twig', [
                'participant' => $participant
    ]);
    }
}