<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\User\EditPasswordType;
use App\Form\User\UserProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController {
    #[Route('/detail/{id}', name: 'user_detail')]
    public function detail(
        int $id,
        UserRepository $userRepo,
        Request $request,
        EntityManagerInterface $em
    ) {
        $user = $userRepo->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur pour cet id');
        }
        $userForm = $this->createForm(UserProfileType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('user_detail', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/profile.html.twig', [
            'userForm' => $userForm
        ]);
    }

    #[Route('/detail/editPassword/{id}')]
    public function editPassword(
        int $id,
        UserRepository $userRepo,
        Request $request,
        EntityManagerInterface $em
    ) {
        $user = $userRepo->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur pour cet id');
        }

        $passwordForm = $this->createForm(EditPasswordType::class, $user);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_detail', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/editPassword.html.twig',[
            'passwordForm' => $passwordForm
        ]);
    }
}