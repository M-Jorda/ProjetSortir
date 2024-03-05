<?php

namespace App\Controller\Sortie;

use App\Entity\Lieu;
use App\Form\AjoutLieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lieu', name: 'lieu-')]
class LieuController extends AbstractController
{
    #[Route('/ajout', name: 'ajout')]
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

    #[Route('/get/{id}', name: 'get')]
    public function trierVilleLieu(int $id, EntityManagerInterface $em) {
        $lieux = $em->getRepository(Lieu::class)->findBy(["ville" => $id]);

        $response = [];
        foreach ($lieux as $lieu) {
            $response[] = [
                'id' => $lieu->getId(),
                'name' => $lieu->getName(),
                'street' => $lieu->getStreet(),
                'latitude' => $lieu->getLatitude(),
                'longitude' => $lieu->getLongitude(),
            ];
        }

        return new JsonResponse($response);
    }

}
