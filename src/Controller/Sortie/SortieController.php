<?php

namespace App\Controller\Sortie;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\CreateSortie\SortieType;
use App\Form\Sécurité\DeleteSortieFormType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @method getDoctrine()
 */
#[Route('/sortie', name: 'sortie-')]
class SortieController extends AbstractController
{

    #[Route('/create', name: 'create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $sortie = new Sortie();
        $etat = $em->getRepository(Etat::class)->find(1);

        //Set l'etat a ouvert et l'organisateur , celui qui est connecté
        $sortie->setEtat($etat);
        $sortie->setOrganisateur($this->getUser());

        //Création du formulaire
        $createsortieForm = $this->createForm(SortieType::class, $sortie)
            ->handleRequest($request);

        if ($createsortieForm->isSubmitted() && $createsortieForm->isValid()) {
            //persist le résultat du formulaire si il est valide
            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Sortie créée');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('sortie/create.html.twig', [
            "sortieForm" => $createsortieForm->createView(),
        ]);
    }

    #[Route('/folder/{id}', name: 'folder', requirements: ['id' => '\d+'], methods: ['POST','GET'])]
    public function folder(int $id, SortieRepository $sortieRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($request->isMethod('POST')) {
            $user = $this->getUser();
            if (!$user) {
                return $this->redirectToRoute('app_login');
            }

            $sortie = $sortieRepository->find($id);
            if (!$sortie) {
                throw $this->createNotFoundException('Dommage');
            }

            if ($sortie->getParticipant()->contains($user)) {
                // L'utilisateur est déjà inscrit à la sortie
                $this->addFlash('error', 'Vous êtes déjà inscrit à cette sortie.');
            } else {
                $sortie->addParticipant($user);
                $entityManager->flush();

                $this->addFlash('success', 'Vous êtes inscrit à la sortie.');
            }
        }

        $sortie = $sortieRepository->find($id);
        $participants = $sortie->getParticipant();
        return $this->render('sortie/folder.html.twig', [
            'sortie' => $sortie,
            'participants' => $participants,
            'user'=>$user,
        ]);
    }

    #[Route('/{id}/unsubscribe', name: 'unsubscribe', requirements: ['id' => '\d+'], methods: ['POST','GET'])]
    public function unsubscribe(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion ou affichez un message d'erreur
            return $this->redirectToRoute('app_login');
        }

        if (!$sortie->getParticipant()->contains($user)) {
            // L'utilisateur n'est pas inscrit à la sortie, redirigez-le vers la liste des sorties ou affichez un message d'erreur
            return $this->redirectToRoute('main_home');
        }

        $sortie->removeParticipant($user);
        $entityManager->persist($sortie);
        $entityManager->flush();

        // Redirigez l'utilisateur vers la liste des sorties ou affichez un message de confirmation
        return $this->redirectToRoute('main_home');
    }

    #[Route('/{id}/subscribe', name: 'subscribe', requirements: ['id' => '\d+'], methods: ['POST','GET'])]
    public function subscribe(Sortie $sortie,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        if ($sortie->isFull() || $sortie->hasUserSubscribed($user)) {
            // La sortie est pleine ou l'utilisateur est déjà inscrit, affichez un message d'erreur et redirigez-le vers la page d'accueil
            $this->addFlash('error', 'Vous ne pouvez pas vous inscrire à cette sortie.');
            return $this->redirectToRoute('main_home');
        }

        $sortie->addParticipant($user);
        $entityManager->flush();

        // L'utilisateur est inscrit à la sortie, affichez un message de succès et redirigez-le vers la page d'accueil
        $this->addFlash('success', 'Vous êtes inscrit à la sortie.');
        return $this->redirectToRoute('main_home');
    }

    #[Route('/{id}/modify', name: 'modify', requirements: ['id' => '\d+'], methods: ['POST','GET'])]
    public function modify(EntityManagerInterface $em, Request $request, Sortie $sortie): Response
    {
        $user = $this->getUser();
        $createForm = $this->createForm(SortieType::class, $sortie)
            ->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Sortie modifiée');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('sortie/modify.html.twig', [
            "sortieForm" => $createForm->createView(),
            'user'=>$user
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST','GET'])]
    public function delete(Sortie $sortie,Request $request, EntityManagerInterface $entityManager): Response
    {

        $form=$this->createForm(DeleteSortieFormType::class, $sortie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $entityManager->remove($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Votre sortie a bien été supprimée');
            return $this->redirectToRoute('main_home');

        }
        return $this->render('sortie/delete.html.twig', [
            'deleteForm' => $form->createView(),
            'sortie'=>$sortie
        ]);
    }
}