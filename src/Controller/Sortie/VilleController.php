<?php

namespace App\Controller\Sortie;

use App\Entity\Ville;
use App\Form\admin\AddCityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ville', name: 'ville-')]
class VilleController extends AbstractController
{

    #[Route('/add', name: 'add', methods: ['GET','POST'])]
    public function addCity(Request $request, EntityManagerInterface $em) {
        $ville = new Ville();

        $villeForm = $this->createForm(AddCityType::class, $ville)
            ->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $em->persist($ville);
            $em->flush();

            $this->addFlash('Success', 'Ville ajoutée');
            return $this->redirectToRoute('admin_panel');
        }

        return $this->render('admin/addCity.html.twig', [
            'villeForm' => $villeForm
        ]);
    }

    #[Route('/get/{id}', name: 'get')]
    public function getVille(int $id, EntityManagerInterface $em) {
        $villes = $em->getRepository(Ville::class)->findBy(["ville" => $id]);

        $response = [];
        foreach ($villes as $ville) {
            $response[] = [
                'id' => $ville->getId(),
                'name' => $ville->getZipCode(),
            ];
        }

        return new JsonResponse($response);
    }

}
