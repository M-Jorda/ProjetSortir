<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CreateSortieType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie/create', name: 'app_sortie_create')]
    public function create(): Response
    {
        $sortie = new Sortie();
        $createForm = $this->createForm(CreateSortieType::class, $sortie);
        return $this->render('sortie/create.html.twig', [
            "createForm" => $createForm->createView()
        ]);

    }

    #[NoReturn] #[Route('/sortie/folder/{id}', name: 'app_sortie_folder')]
    public function folder(int $id, SortieRepository $sortieRepository, UserRepository $userRepository): Response
    {
        $sortie = $sortieRepository->find($id);
        if (!$sortie) {
            throw $this->createNotFoundException('Dommage');
        }
        $participants = $sortie->getParticipant();
        return $this->render('sortie/folder.html.twig', [
            'sortie' => $sortie,
            'participants' => $participants,
        ]);
    }

    #[Route('/sortie/modify', name: 'app_sortie_modify')]
    public function modify(): Response
    {
        return $this->render('sortie/modify.html.twig');
    }

    #[Route('/sortie/delete', name: 'app_sortie_delete')]
    public function delete(): Response
    {
        return $this->render('sortie/delete.html.twig');
    }
}
