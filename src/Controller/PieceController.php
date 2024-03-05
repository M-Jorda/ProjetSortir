<?php

namespace App\Controller;

use App\Entity\Piece;
use App\Repository\PieceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\isEmpty;

class PieceController extends AbstractController
{
    #[Route('/piece2', name: 'piece_point2', methods: ['GET'])]
    public function piece2(EntityManagerInterface $entityManager, PieceRepository $pieceRepository)
    {
        $user = $this->getUser();

        $piece2 = $user->getPiece();

        $piece2->setPiece2(true);

        $user->setPiece($piece2);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('point/point.html.twig',[
            'user'=>$user

        ]);
    }
    #[Route('/piece3', name: 'piece_point3', methods: ['GET'])]
    public function piece3(EntityManagerInterface $entityManager, PieceRepository $pieceRepository)
    {
        $user = $this->getUser();

        $piece3 = $user->getPiece();

        $piece3->setPiece3(true);

        $user->setPiece($piece3);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('point/point.html.twig',[
            'user'=>$user

        ]);
    }
    #[Route('/piece4', name: 'piece_point4', methods: ['GET'])]
    public function piece4(EntityManagerInterface $entityManager, PieceRepository $pieceRepository)
    {
        $user = $this->getUser();

        $piece4 = $user->getPiece();

        $piece4->setPiece4(true);

        $user->setPiece($piece4);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('point/point.html.twig',[
            'user'=>$user

        ]);
    }
    #[Route('/piece1', name: 'piece_point1', methods: ['GET'])]
    public function piece1(EntityManagerInterface $entityManager, PieceRepository $pieceRepository)
    {
        $user = $this->getUser();

        $piece1 = $user->getPiece();

        $piece1->setPiece1(true);

        $user->setPiece($piece1);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('point/point.html.twig',[
            'user'=>$user

        ]);
    }
    #[Route('/piece5', name: 'piece_point5', methods: ['GET'])]
    public function piece5(EntityManagerInterface $entityManager, PieceRepository $pieceRepository)
    {
        $user = $this->getUser();

        $piece5 = $user->getPiece();

        $piece5->setPiece5(true);

        $user->setPiece($piece5);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('point/point.html.twig',[
            'user'=>$user

        ]);
    }
    #[Route('/reset', name: 'piece_reset', methods: ['GET'])]
    public function reset(EntityManagerInterface $entityManager, PieceRepository $pieceRepository)
    {
        $user = $this->getUser();

        $piece5 = $user->getPiece();

        $piece5->setPiece5(false)->setPiece4(false)->setPiece3(false)->setPiece2(false)->setPiece1(false);

        $user->setPiece($piece5);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('point/point.html.twig',[
            'user'=>$user

        ]);
    }

    #[Route('/piece/quete', name: 'piece_quete', methods: ['GET'])]
    public function queteDesPieces(EntityManagerInterface $entityManager, PieceRepository $pieceRepository)
    {
        $user = $this->getUser();
        $piece = new Piece();

        $user->setPiece($piece);
        $user->setRoles(['ROLE_PIECE']);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('point/point.html.twig',[
            'user'=>$user,

        ]);
    }

    #[Route('/piece/redirection', name: 'piece_redirection', methods: ['GET'])]
    public function redirectionPiece(EntityManagerInterface $entityManager, PieceRepository $pieceRepository)
    {
        $user = $this->getUser();




        return $this->render('point/point.html.twig',[
            'user'=>$user,

        ]);
    }

}

