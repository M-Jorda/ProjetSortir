<?php

namespace App\Controller\Utilisateur;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\User\EditPasswordType;
use App\Form\User\UserProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class UserController extends AbstractController {
    #[Route('/detail/{id}', name: 'user_detail', requirements: ['id' => '\d+'], methods : ['GET','POST'])]

    public function detail (SluggerInterface       $slugger,
                            int                    $id,
                            UserRepository         $userRepo,
                            Request                $request,
                            EntityManagerInterface $em, Filesystem $filesystem
    ) {
        $user = $userRepo->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur pour cet id');
        }
        $userForm = $this->createForm(UserProfileType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $pictureFile = $userForm->get('picture')->getData();

            if($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('photo_profil_directory'),
                        $newFilename
                    );

                    if ($user->getPicture())
                    {
                        $oldPhotoPath = $this->getParameter('photo_profil_directory').'/'.$user->getPicture();
                        if ($filesystem->exists($oldPhotoPath))
                        {
                            $filesystem->remove($oldPhotoPath);
                        }
                    }
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setPicture($newFilename);
            }
                     else{
                        $user->setPicture($user->getPicture());

            }
                     $em->persist($user);
                     $em->flush();

                    return $this->redirectToRoute('user_detail', [
                    'id' => $user->getId()
            ]);

        }
                    return $this->render('user/profile.html.twig', [
                        'user' => $user,
                        'userForm' => $userForm->createView()
            ]);
    }

    #[Route('/detail/editPassword/{id}', requirements: ['id' => '\d+'], methods : ['GET','POST'])]
    public function editPassword(
        int $id,
        UserRepository $userRepo,
        Request $request,
        EntityManagerInterface $em,
         UserPasswordHasherInterface $userPasswordHasher
    ) {
        $user = $userRepo->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur pour cet id');
        }

        $passwordForm = $this->createForm(EditPasswordType::class, $user);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $passwordForm->get('password')->getData()
                )
            );
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
