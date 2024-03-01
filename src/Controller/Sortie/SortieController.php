<?php

namespace App\Controller\Sortie;


use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\CreateSortie\CreateSortieType;
use App\Form\CreateSortie\SortieType;
use App\Form\CreateSortie\VilleType;
use App\Form\SortieVilleType;
use App\Form\Sécurité\DeleteSortieFormType;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SortieController extends AbstractController
{




    #[Route('/sortie/create', name: 'app_sortie_create')]
    public function create(EntityManagerInterface $em, Request $request, LieuRepository $lieuRepository, VilleRepository $villeRepository): Response
    {
        $sortie = new Sortie();
        $etat = $em->getRepository(Etat::class)->find(1);

        $villes = $villeRepository->findAll();

        $sortie->setEtat($etat);
        $sortie->setOrganisateur($this->getUser());

        $createsortieForm = $this->createForm(SortieType::class, $sortie)
            ->handleRequest($request);


        if ($createsortieForm->isSubmitted() && $createsortieForm->isValid()) {

            $em->persist($sortie);


            $em->flush();

            $this->addFlash('success', 'Sortie créée');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('sortie/create.html.twig', [
            "sortieForm" => $createsortieForm->createView(),
            'villes' => $villes
        ]);
    }




    #[Route('/sortie/folder/{id}', name: 'app_sortie_folder', methods: ['POST','GET'])]
    public function folder(int $id, SortieRepository $sortieRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
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
        ]);
    }
    #[Route('/sortie/{id}/unsubscribe', name: 'app_sortie_unsubscribe', methods: ['POST','GET'])]
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
    #[Route('/sortie/{id}/subscribe', name: 'app_sortie_subscribe', methods: ['POST','GET'])]
    public function subscribe(Request $request, Sortie $sortie, SortieRepository $sortieRepository, EntityManagerInterface $entityManager): Response
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



    #[Route('/sortie/modify', name: 'app_sortie_modify')]
    public function modify(EntityManagerInterface $em, Request $request): Response
    {
        $sortie = new Sortie();
        $ville = new Ville();
        $lieu = new Lieu();
        $etat = new Etat();

        $sortie->setEtat($etat->setLibelle(1));

        $createForm = $this->createForm(\App\Form\CreateSortie\CreateSortieType::class, [$sortie, $ville, $lieu])
            ->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $data = $createForm->getData();
            $em->persist($data['sortie']);
            $em->persist($data['lieu']);
            $em->persist($data['ville']);
            $em->flush();

            $this->addFlash('success', 'Sortie modifié');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('sortie/modify.html.twig', [
            "createForm" => $createForm->createView(),
        ]);
    }


    #[Route('/sortie/delete/{id}', name: 'app_sortie_delete')]
    public function delete(Sortie $sortie,Request $request, EntityManagerInterface $entityManager): Response
    {
        $etat = new Etat();
        $form=$this->createForm(DeleteSortieFormType::class, $etat);
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

