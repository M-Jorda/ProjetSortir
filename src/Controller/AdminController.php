<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\admin\AddCampusType;
use App\Form\admin\AddCityType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/manage', name: 'manage')]
    public function manageUser(UserRepository $userRepo) {
        $users = $userRepo->findAll();

        return $this->render('admin/manageUser.html.twig', [
            "users" => $users
        ]);
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

    #[Route('/', name: 'panel')]
    public function adminPanel() {
        return $this->render('admin/panel.html.twig');
    }

    #[Route('/ajouterVille', name: 'addCity')]
    public function addCity(Request $request, EntityManagerInterface $em) {
        $ville = new Ville();

        $villeForm = $this->createForm(AddCityType::class, $ville)
            ->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $em->persist($ville);
            $em->flush();

            $this->addFlash('Success', 'Ville ajoutée');
            return $this->redirectToRoute('admin_panel');
        }

        return $this->render('admin/addCity.html.twig', [
            'villeForm' => $villeForm
        ]);
    }

    #[Route('/ajouterCampus', name: 'addCampus')]
    public function addCampus(Request $request, EntityManagerInterface $em) {
        $campus = new Campus();

        $campusForm = $this->createForm(AddCampusType::class, $campus)
            ->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $em->persist($campus);
            $em->flush();

            $this->addFlash('success', 'Campus ajouté');
            return $this->redirectToRoute('admin_panel');
        }

        return $this->render('admin/addCampus.html.twig', [
            'campusForm' => $campusForm
        ]);
    }

}