<?php

namespace App\Controller\Utilisateur;

use App\Entity\Campus;
use App\Form\admin\AddCampusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CampusController extends AbstractController
{

    #[Route('/campus/add', name: 'campus-add', methods: ['GET','POST'])]
    public function addCampus(Request $request, EntityManagerInterface $em) {
        $campus = new Campus();
        $campuss = $em->getRepository(Campus::class)->findAll();

        $campusForm = $this->createForm(AddCampusType::class, $campus)
            ->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $em->persist($campus);
            $em->flush();

            $this->addFlash('success', 'Campus ajouté');
            return $this->redirectToRoute('admin_panel');
        }

        return $this->render('admin/addCampus.html.twig', [
            'campusForm' => $campusForm,
            'campuss' => $campuss
        ]);
    }
}