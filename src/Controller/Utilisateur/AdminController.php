<?php

namespace App\Controller\Utilisateur;

use App\Entity\User;
use App\Form\Sécurité\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/manage', name: 'manage', methods: ['GET', 'POST'])]
    public function manageUser(UserRepository $userRepo) {

        $users = $userRepo->findAll();

        return $this->render('admin/manageUser.html.twig', [
            "users" => $users,

        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function addUser(
        UserPasswordHasherInterface $userPasswordHasher,
        Request $request,
        EntityManagerInterface $em
    ) {
       $user = new User();
       $date = new \DateTime();
       $user->setRoles(["ROLE_USER"]);
       $user->setBlocked(0);
       $user->setCreatedDate($date);

       $userForm = $this->createForm(RegistrationFormType::class, $user);
       $userForm->handleRequest($request);

       if ($userForm->isSubmitted() && $userForm->isValid()) {
           $user->setPassword(
               $userPasswordHasher->hashPassword(
                   $user,
                   $userForm->get('plainPassword')->getData()
               )
           )
           ->setPseudo($user->getFirstName() . $date->format('Y-m-d'));
           $em->persist($user);
           $em->flush();

           $this->addFlash('success', 'Utilisateur ajouté');
           return $this->redirectToRoute( 'admin_manage');
       }

       return $this->render('admin/addUser.html.twig', [
           'userForm' => $userForm->createView()
       ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['GET','POST'])]
    public function deleteUser(int $id, EntityManagerInterface $em) {

        $userRepo = $em->getRepository(User::class);
        $user = $userRepo->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        } else {
            $em->remove($user);
            $em->flush();

            $this->addFlash('succes', 'Cet utilisateur à été supprimé');
        }
        return $this->redirectToRoute('admin_manage');
    }

    #[Route('/block/{id}', name: 'block', methods: ['GET', 'POST'])]
    public function blockUser(int $id, EntityManagerInterface $em) {
        $userRepo = $em->getRepository(User::class);
        $user = $userRepo->find($id);
        $users = $userRepo->findAll();

        if (!$user) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        $user->setBlocked(!$user->isBlocked());
        $em->flush();
        if ($user->isBlocked()) {
            $this->addFlash('success', 'L\'utilisateur à été bloqué');
        } else {
            $this->addFlash('success', 'L\'utilisateur à été debloqué');
        }
        return $this->redirectToRoute('admin_manage');
    }

    #[Route('/', name: 'panel', methods: ['GET','POST'])]
    public function adminPanel() {
        $user = $this->getUser();
        return $this->render('admin/panel.html.twig',[
            'user'=>$user
        ]);
    }
}