<?php

namespace App\Controller;

use App\DTO\SortieDTO;
use App\Entity\Campus;
use App\Form\SortieDTOType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home', methods: ['GET'])]
    public function home(Request $request, SortieRepository $sortieRepository)
    {
        $sortieDTO = new SortieDTO();
        $form = $this->createForm(SortieDTOType::class, $sortieDTO);
        $form->handleRequest($request);

        $sorties = $sortieRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $data= $form->getData();
            // Récupérer les données du formulaire
            $name = $data->getName();
            $filterDate = $data->getFilterDate();
            $filterDateMax = $data->getFilterDateMax();

            // Utiliser la méthode du repository pour filtrer et trier les sorties
            $sorties = $sortieRepository->findByNameAndDate($data,$name, $filterDate, $filterDateMax);

        }


        return $this->render('main/home.html.twig', [
            'form' => $form->createView(),
            'sorties' => $sorties,
        ]);
    }


//    public function home(Request $request, SortieRepository $sortieRepository)
//    {
//
//        $nameForm = $this->createForm(SortieType::class);
//        $nameForm->handleRequest($request);
//
//        $searchName = $request->query->get('name');
//        $filterDate = $request->query->get('filterDate'); // Récupérer la date de filtrage
//        $filterDateMax = $request->query->get('filterDateMax');
//
//        // Modifier la requête pour filtrer par date
//        $filteredSorties = $sortieRepository->findByNameAndDate($searchName, $filterDate, $filterDateMax);
//
//        return $this->render('main/home.html.twig', [
//            'nameForm' => $nameForm,
//            'sorties' => $filteredSorties,
//            'dateMin' => $filterDate,
//            'dateMax' => $filterDateMax,
//            'searchName' => $searchName,
//        ]);


}