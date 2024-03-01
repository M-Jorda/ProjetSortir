<?php

namespace App\Controller\Sécurité;

use App\Entity\Campus;
use App\Entity\User;
use App\Form\Sécurité\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods : ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $campus = new Campus();
        $campus->setName('Lyon');
        $user->setPseudo('HelloKitty');

        // Check si le campus existe
        $existingCampus = $entityManager->getRepository(Campus::class)->findOneBy(['name' => 'Lyon']);
        if (!$existingCampus) {
            // Sinon persist
            $entityManager->persist($campus);
        } else {
            // Si oui utilise l'existant
            $campus = $existingCampus;
        }

        $user->setCampus($campus);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setBlocked(0);
        $user->setCreatedDate(new \DateTimeImmutable());
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
