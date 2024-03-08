<?php

namespace App\Controller\Admin;

use App\DTO\CampusDTO;
use App\Entity\Campus;
use App\Form\admin\AddCampusType;
use App\Form\admin\CampusDTOType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/campus', name: 'campus-')]
class CampusController extends AbstractController
{

    #[Route('/manage', name: 'manage', methods: ['GET','POST'])]
    public function addCampus(Request $request, EntityManagerInterface $em, CampusRepository $campusRepo) {

        $filter = new CampusDTO();
        $campus = new Campus();
        $campuss = $em->getRepository(Campus::class)->findAll();

        $campusForm = $this->createForm(AddCampusType::class, $campus)
            ->handleRequest($request);
        $filterForm = $this->createForm(CampusDTOType::class, $filter)
            ->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $campusFiltered = $filter->getName();
            $campuss = $campusRepo->findByName($campusFiltered);
        }

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $em->persist($campus);
            $em->flush();

            $this->addFlash('success', 'Campus ajouté');
            return $this->redirectToRoute('campus-manage');
        }

        return $this->render('admin/addCampus.html.twig', [
            'campusForm' => $campusForm,
            'filterForm' => $filterForm,
            'campuss' => $campuss,
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
        return $this->redirectToRoute('campus-manage');
    }
}