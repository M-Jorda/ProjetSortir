<?php

namespace App\Controller\Utilisateur;

use App\Entity\Campus;
use App\Form\admin\AddCampusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/campus', name: 'campus-')]
class CampusController extends AbstractController
{

    #[Route('/add', name: 'add', methods: ['GET','POST'])]
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

    #[Route('delete/{id}', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(int $id, EntityManagerInterface $em) {
        $campus = $em->getRepository(Campus::class)->find($id);

        if (!$campus) {
            throw $this->createNotFoundException('Ce campus n\'existe pas');
        } else {
            $em->remove($campus);
            $em->flush();

            $this->addFlash('success', 'Ce campus é été correctement supprimé');
        }
        return $this->redirectToRoute('campus-add');
    }
}