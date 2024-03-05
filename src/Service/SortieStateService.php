<?php

namespace App\Service;


use App\Entity\Etat;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;






class SortieStateService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEtatPasse(): Etat
    {
        $etatRepository = $this->entityManager->getRepository(Etat::class);
        $etatFerme = $etatRepository->findOneBy(['libelle' => 'Passé']);

        if (!$etatFerme instanceof Etat) {
            throw new \RuntimeException('L\'état "Fermé" n\'existe pas dans la base de données.');
        }

        return $etatFerme;
    }

    public function getEtatObject(Sortie $sortie): Etat
    {
        $now = new \DateTime();
        $tomorrow = new \DateTime('tomorrow');

        if ($now > $sortie->getLimiteDateInscription()) {
            return $this->getEtatPasse();
        }
        if ($sortie->getLimiteDateInscription() <= $now && $now <= $tomorrow) {
            return $this->getEtatEnCours();
        }
        if ($sortie->getParticipant()->count() == $sortie->getMaxInscriptionsNumber()) {
            return $this->getEtatComplet();
        }

        return $this->getEtatOuvert();
    }

    public function getEtatOuvert(): Etat
    {
        $etatRepository = $this->entityManager->getRepository(Etat::class);
        $etatOuvert = $etatRepository->findOneBy(['libelle' => 'Ouvert']);

        if (!$etatOuvert instanceof Etat) {
            throw new \RuntimeException('L\'état "Ouvert" n\'existe pas dans la base de données.');
        }

        return $etatOuvert;
    }
    public function getEtatEnCours(): Etat
    {
        $etatRepository = $this->entityManager->getRepository(Etat::class);
        $etatOuvert = $etatRepository->findOneBy(['libelle' => 'en cours']);

        if (!$etatOuvert instanceof Etat) {
            throw new \RuntimeException('L\'état "en cours" n\'existe pas dans la base de données.');
        }

        return $etatOuvert;
    }

    private function getEtatComplet(): Etat
    {
        $etatRepository = $this->entityManager->getRepository(Etat::class);
        $etatOuvert = $etatRepository->findOneBy(['libelle' => 'Complet']);

        if (!$etatOuvert instanceof Etat) {
            throw new \RuntimeException('L\'état "Complet" n\'existe pas dans la base de données.');
        }

        return $etatOuvert;
    }

}