<?php

namespace App\Controller\Sortie;

use App\Controller\DTOSortie;
use App\Controller\DTOSortieType;
use App\Controller\Request;

class SortieDTOController
{
    public function new(Request $request)
    {
        $dtoSortie = new DTOSortie();
        $form = $this->createForm(DTOSortieType::class, $dtoSortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Utilisez les données dans $dtoSortie comme nécessaire
            // Par exemple, enregistrer les données dans la base de données
        }

        return $this->render('sortie/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}