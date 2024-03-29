<?php

namespace App\Controller\Admin;

use App\Controller\Sortie\Response;
use App\DTO\VilleDTO;
use App\Entity\Ville;
use App\Form\admin\AddCityType;
use App\Form\admin\VilleDTOType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/ville', name: 'ville-')]
class VilleController extends AbstractController
{

    #[Route('/manage', name: 'manage', methods: ['GET','POST'])]
    public function addCity(Request $request, EntityManagerInterface $em, VilleRepository $villeRepo) {

        $filter = new VilleDTO();
        $ville = new Ville();
        $villes = $em->getRepository(Ville::class)->findAll();

        $villeForm = $this->createForm(AddCityType::class, $ville)
            ->handleRequest($request);
        $filterForm = $this->createForm(VilleDTOType::class, $filter)
            ->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $villeFiltered = $filter->getName();
            $villes = $villeRepo->findByName($villeFiltered);
        }

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $em->persist($ville);
            $em->flush();

            $this->addFlash('Success', 'Ville ajoutée');
            return $this->redirectToRoute('ville-manage');
        }

        return $this->render('admin/addCity.html.twig', [
            'villeForm' => $villeForm,
            'filterForm' => $filterForm,
            'villes' => $villes
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(int $id, EntityManagerInterface $em) {
        $ville = $em->getRepository(Ville::class)->find($id);

        if(!$ville) {
            throw $this->createNotFoundException('Cette ville n\existe pas');
        } else {
            $em->remove($ville);
            $em->flush();

            $this->addFlash('success', 'Celle ville à été correctement supprimé');
        }
        return $this->redirectToRoute('ville-manage');
    }

    #[Route('/get/{id}', name: 'get')]
    public function getVille(int $id, EntityManagerInterface $em) {
        $ville = $em->getRepository(Ville::class)->find($id);

        if (!$ville) {
            return new JsonResponse(['message' => 'Ville non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $response = [
            'id' => $ville->getId(),
            'zipCode' => $ville->getZipCode(), // Assurez-vous que votre entité Ville a un getter pour le code postal
        ];

        return new JsonResponse($response);
    }

}
