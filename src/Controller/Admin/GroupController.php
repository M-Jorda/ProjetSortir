<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Entity\GroupStatus;
use App\Form\admin\group\StatusType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/group/manage', name: 'admin_group_')]
class GroupController extends AbstractController
{
#[Route('/', name: 'manage')]
    public function manageGroup(GroupRepository $groupRepo) {

        $groups = $groupRepo->findAll();

        return $this->render('admin/manageGroup.html.twig', [
            'groups' => $groups
        ]);
    }

    #[Route('/status/{id}', name: 'manage_status')]
    public function setStatus(
        int $id,
        Group $grp,
        GroupRepository $groupRepo,
        Request $request,
        EntityManagerInterface $em) {

    $group = $groupRepo->find($id);

    $form = $this->createForm(StatusType::class, $group)
        ->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist();
        $em->flush();

        $this->addFlash('success', 'Le groupe à bien été modifié');
        $this->redirectToRoute('admin_group_manage');
    }


    return $this->render('admin/manageGroupStatus.html.twig', [
        'group' => $group,
        'form' => $form
    ]);
    }
}