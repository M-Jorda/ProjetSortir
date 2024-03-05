<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportUsersService
{


    public function __construct (private UserRepository $userRepository, private CampusRepository $campusRepository, private EntityManagerInterface $em)
    {

    }

    /**
     * @throws OptimisticLockException
     * @throws UnavailableStream
     * @throws ORMException
     */
    public function importUsers(SymfonyStyle $io):void
    {
        $io->title('Importation des utilisateurs');

        $users = $this->readCsvFile();

        dump($users);

        $io->progressStart(count($users));

        foreach ($users as $arrayUser) {
            $io->progressAdvance();
            $user = $this->createUser($arrayUser);
            $this->em->persist($user);


        }

            $this->em->flush();

            $io->progressFinish();
            $io->success('Importation terminée');

    }

    /**
     * @throws UnavailableStream
     */
    private function readCsvFile() : Reader
    {
       $csv = Reader::createFromPath('%kernel.root.dir%/../import/users.csv', 'r');
       $csv->setHeaderOffset(0);

       return $csv;
    }

    private function createUser(array $arrayUser): User
    {
            $user = new User();

        $campusId = $arrayUser['campus_id'];
        $campus = $this->campusRepository->find($campusId);

        $createdDate = new \DateTime($arrayUser['created_date']);

        $user
            ->setEmail($arrayUser['email'])
    //        ->setRoles([$arrayUser['roles']])
            ->setPassword($arrayUser['password'])
            ->setLastname($arrayUser['lastname'])
            ->setFirstName($arrayUser['first_name'])
            ->setPhoneNumber($arrayUser['phone_number'])
            ->setBlocked($arrayUser['blocked'])
            ->setCampus($campus)
            ->setPseudo($arrayUser['pseudo'])
            ->setCreatedDate($createdDate)
            ->setUpdatedDate(new \DateTime());

        return $user;

    }

}
