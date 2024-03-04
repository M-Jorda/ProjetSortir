<?php

namespace App\Controller;

use App\DTO\SortieDTO;
use App\Entity\Campus;
use App\Form\SortieDTOType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use App\Service\SortieStateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use function PHPUnit\Framework\isEmpty;

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

        $form = $this->createForm(SortieDTOType::class, $sortieDTO);
        $form->handleRequest($request);

        $sorties = $sortieRepository->findBy([],['startDate'=>'DESC'],null,null,new \DateTime('-30 days'));

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
        ]);
    }


}