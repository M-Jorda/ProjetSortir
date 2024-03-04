<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\AjoutLieuType;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/trier', name: 'trier')]
    public function trierVilleLieu(int $id, EntityManagerInterface $em) {
        $lieux = $em->getRepository(Ville::class)->getLieuPerVille($id);

        $response = [];
        foreach ($lieux as $lieu) {
            $response[] = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getName()
            ];
        }

        return new Response(json_encode($response), 200, ['Content-Type' => 'application/json']);

    }

}
