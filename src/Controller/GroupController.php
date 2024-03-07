<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/group', name: 'group_')]
class GroupController extends AbstractController
{
    #[Route('/panel', name: 'panel', methods: ['GET','POST'])]
    public function panel() {
        $idGroup = $this->getUser();

        return $this->render('group/panel.html.twig', );
}

    #[Route('/create', name: 'create', methods: ['GET','POST'])]
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepo) {

        $group = new Group();
        $date = new \DateTime();

        $groupForm = $this->createForm(GroupType::class, $group)
            ->handleRequest($request);


        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            $user = $this->getUser();
            $group->setCreatedDate($date);

            $em->persist($group);
            $em->flush();

            $idGroup = $group->getId();
            $group->addUser($user);

            $this->addFlash('success', 'Le groupe é bien été créé');
            return $this->redirectToRoute('group_detail', [
                'idGroup' => $idGroup,
            ]);
        }


        return $this->render('group/create.html.twig', [
            'groupForm' => $groupForm
        ]);
    }

    #[Route('/detail', name: 'detail', methods: ['GET','POST'])]
    public function detail() {


        return $this->render('group/detail.html.twig');
    }

    #[Route('s', name: 'list', methods: ['GET','POST'])]
    public function index(): Response
    {
        return $this->render('group/index.html.twig', [
            'controller_name' => 'GroupController',
        ]);
    }
}
