<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddUserType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/manage', name: 'manage')]
    public function manageUser() {
        return $this->render('admin/manageUser.html.twig');
    }

    #[Route('/add', name: 'add')]
    public function addUser(
        UserPasswordHasherInterface $userPasswordHasher,
        Request $request,
        EntityManagerInterface $em
    ) {
       $user = new User();
       $user->setRoles(["ROLE_USER"]);
       $user->setBlocked(0);

       $userForm = $this->createForm(RegistrationFormType::class, $user);
       $userForm->handleRequest($request);

       if ($userForm->isSubmitted() && $userForm->isValid()) {
           $user->setPassword(
               $userPasswordHasher->hashPassword(
                   $user,
                   $userForm->get('plainPassword')->getData()
               )
           );
           $em->persist($user);
           $em->flush();

           $this->addFlash('success', 'Utilisateur ajouté');
           return $this->redirectToRoute( 'user_detail', ['id' => $user->getId()]);
       }

       return $this->render('admin/addUser.html.twig', [
           'userForm' => $userForm->createView()
       ]);
    }

    #[Route('/delete', name: 'delete')]
    public function deleteUser() {
        return $this->render('admin/deleteUser.html.twig');
    }

    #[Route('/block', name: 'block')]
    public function blockUser() {
        return $this->render('admin/blockUser.html.twig');
    }

}