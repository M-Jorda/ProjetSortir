<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\CreateSortieType;
use App\Form\SortieVilleType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie/create', name: 'app_sortie_create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $sortie = new Sortie();
        $ville = new Ville();

        $createForm = $this->createFormBuilder([$ville, $sortie])
        ->add('sortie', CreateSortieType::class)
        ->add('ville', SortieVilleType::class)
        ->getForm()
        ->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {

            $em->persist($sortie);
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'Sortie créée');
            return $this->redirectToRoute( 'main_home');
        }

        return $this->render('sortie/create.html.twig', [
            "createForm" => $createForm->createView()
        ]);

    }

    #[Route('/sortie/folder/{id}', name: 'app_sortie_folder')]
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
