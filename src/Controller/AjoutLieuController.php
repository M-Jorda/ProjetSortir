<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\AjoutLieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AjoutLieuController extends AbstractController
{
    #[Route('/ajout/lieu', name: 'app_ajout_lieu')]
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $newLieu = new Lieu();

        $form = $this->createForm(AjoutLieuType::class, $newLieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($newLieu);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu ajouté avec succès.');
            return $this->redirectToRoute('app_sortie_create');
        }

        return $this->render('ajout_lieu/index.html.twig', [
            'form' => $form->createView(), // Passer la vue du formulaire, pas le formulaire lui-même
        ]);
    }
}
