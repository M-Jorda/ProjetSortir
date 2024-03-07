<?php

namespace App\Controller\Groups;

use App\Entity\Group;
use App\Form\admin\group\GroupType;
use App\Repository\GroupRepository;
use App\Repository\GroupStatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/group', name: 'group_')]
class GroupController extends AbstractController
{
    #[Route('/panel', name: 'panel', methods: ['GET'])]
    public function panel() {

        return $this->render('group/panel.html.twig', );
}

    #[Route('/create', name: 'create', methods: ['GET','POST'])]
    public function create(
        Request $request, EntityManagerInterface $em, UserRepository $userRepo, GroupStatusRepository $gsRepo) {

        $group = new Group();

        $groupForm = $this->createForm(GroupType::class, $group)
            ->handleRequest($request);

        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            $user = $userRepo->find($this->getUser());
            $group->addUser($user);
            $group->setStatus($gsRepo->find(3));

            $em->persist($group);
            $em->flush();

            $idGroup = $group->getId();

            $this->addFlash('success', 'Le groupe é bien été créé');
            return $this->redirectToRoute('group_details', [
                'id' => $idGroup,
                'group' => $group
            ]);
        }

        return $this->render('group/create.html.twig', [
            'groupForm' => $groupForm
        ]);
    }

    #[Route('/details', name: 'details', methods: ['GET','POST'])]
    public function details(GroupRepository $groupRepo, Request $request, EntityManagerInterface $em) {

        $groups = $groupRepo->findAll();


        return $this->render('group/detail.html.twig', [
            'groups' => $groups
        ]);
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail(int $id, GroupRepository $groupRepo, Request $request, EntityManagerInterface $em) {

        $group = $groupRepo->find($id);

        $form = $this->createForm(GroupType::class, $group)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }


        return $this->render('group/detailId.html.twig', [
            'group' => $group,
        ]);
    }

    #[Route('s', name: 'list', methods: ['GET','POST'])]
    public function index(GroupRepository $groupRepo): Response
    {
        $groups = $groupRepo->findAll();


        return $this->render('group/list.html.twig', [
            'groups' => $groups,
        ]);
    }

}
