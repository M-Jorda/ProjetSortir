<?php

namespace App\Controller\Sortie;

use App\DTO\SortieDTO;
use App\Form\Sortie\SortieDTOType;
use App\Repository\SortieRepository;
use App\Service\SortieStateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class MainController extends AbstractController
{
    #[Route('/', name: 'main_home', methods: ['POST', 'GET'])]
    public function home(Request                $request,
                         SortieRepository       $sortieRepository,
                         EntityManagerInterface $entityManager,
                         SortieStateService     $sortieStateService)
    {
        $sortieDTO = new SortieDTO();


        $user = $this->getUser();

        $dateNow = new \DateTime('now');


        $form = $this->createForm(SortieDTOType::class, $sortieDTO);
        $form->handleRequest($request);


        $sorties = $sortieRepository->trierSortie30j();


        if ($form->isSubmitted() && $form->isValid()) {

            $name = $sortieDTO->getName();
            $filterDate = $sortieDTO->getFilterDate();
            $filterDateMax = $sortieDTO->getFilterDateMax();
            $checkBoxOrga = $sortieDTO->getCheckboxOrga();
            $checkBoxInscrit = $sortieDTO->isCheckBoxInscrit();
            $checkBoxNotInscrit = $sortieDTO->isCheckBoxNotInscrit();
            $sortiePasse = $sortieDTO->isSortiePasse();
            $campus = $sortieDTO->getCampus();
            $sorties = $sortieRepository->findByNameAndDate(
                $name,
                $filterDate,
                $filterDateMax,
                $checkBoxOrga,
                $checkBoxInscrit,
                $checkBoxNotInscrit,
                $sortiePasse,
                $campus
            );


        }
        foreach ($sorties as $sortie) {
            $sortie->updateEtat($sortieStateService);
        }



        $entityManager->flush();
        return $this->render('main/home.html.twig', [
            'sorties' => $sorties,
            'form' => $form->createView(),
            'user' => $user,
            'sortieStateService' => $sortieStateService,
            'date'=>$dateNow
        ]);
    }



}