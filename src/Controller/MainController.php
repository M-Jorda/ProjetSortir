<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController {
    #[Route('/', name: 'main_home', methods: ['GET'])]
    public function home(Request $request, SortieRepository $sortieRepository)
    {

        $nameForm = $this->createForm(SortieType::class);
        $nameForm->handleRequest($request);

        $searchName = $request->query->get('name');
        $filterDate = $request->query->get('filterDate'); // Récupérer la date de filtrage
        $filterDateMax = $request->query->get('filterDateMax');

        // Modifier la requête pour filtrer par date
        $filteredSorties = $sortieRepository->findByNameAndDate($searchName, $filterDate, $filterDateMax);

        return $this->render('main/home.html.twig', [
            'nameForm' => $nameForm,
            'sorties' => $filteredSorties,
            'dateMin' => $filterDate,
            'dateMax' => $filterDateMax,
            'searchName' => $searchName,
        ]);
    }

}