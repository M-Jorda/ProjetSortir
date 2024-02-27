<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\Ville;
use App\Entity\Lieu;
use App\Form\CreateSortieType;
use App\Form\SortieVilleType;
use App\Form\SortieLieuType;
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
        $lieu = new Lieu();

        $createForm = $this->createFormBuilder()
            ->add('sortie', CreateSortieType::class, [
                'data' => $sortie,
                'label' => ' ',
            ])
            ->add('ville', SortieVilleType::class, [
                'data' => $ville,
                'label' => ' ',
            ])
            ->add('lieu', SortieLieuType::class, [
                'data' => $lieu,
                'label' => ' ',
            ])
            ->getForm();

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $em->persist($sortie);
            $em->persist($ville);
            $em->persist($lieu);
            $em->flush();

            $this->addFlash('success', 'Sortie créée');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('sortie/create.html.twig', [
            "createForm" => $createForm->createView(),
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
    public function modify(EntityManagerInterface $em, Request $request): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(CreateSortieType::class, $sortie);

        $form->handleRequest($request);

        if ($form -> isSubmitted()&&$form -> isValid()){
            $em->persist($sortie);

            $em->flush();

            return $this->redirectToRoute('app_main_home');

        }
        return $this->render('sortie/modify.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/sortie/delete', name: 'app_sortie_delete')]
    public function delete(): Response
    {
        return $this->render('sortie/delete.html.twig');
    }
}
