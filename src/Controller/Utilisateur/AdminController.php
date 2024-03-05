<?php

namespace App\Controller\Utilisateur;

use App\Entity\Campus;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\admin\AddCampusType;
use App\Form\admin\AddCityType;
use App\Form\Sécurité\RegistrationFormType;
use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use function Symfony\Component\Clock\now;

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

    #[Route('/ajouterVille', name: 'addCity', methods: ['GET','POST'])]
    public function addCity(Request $request, EntityManagerInterface $em, VilleRepository $villeRepository) {

        $villes = $villeRepository->findAll();

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
            'villeForm' => $villeForm,
            'villes'=> $villes
        ]);
    }

    #[Route('/ajouterCampus', name: 'addCampus', methods: ['GET','POST'])]
    public function addCampus(Request $request, EntityManagerInterface $em, CampusRepository $campusRepository) {
        $campuss = $campusRepository->findAll();

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
            'campusForm' => $campusForm,
            'campuss'=>$campuss
        ]);
    }

}