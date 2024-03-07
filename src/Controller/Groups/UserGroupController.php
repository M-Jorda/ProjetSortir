<?php

namespace App\Controller\Groups;

use App\Entity\Campus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/group/user', name: 'group_user_')]
class UserGroupController extends AbstractController
{
    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em) {
        $group = $em->getRepository(Campus::class)->find($id);

        if (!$group) {
            throw $this->createNotFoundException('Ce groupe n\'existe pas');
        } else {
            $em->remove($group);
            $em->flush();

            $this->addFlash('success', 'Cet utilisateur à été correctement supprimé du groupe');
        }
        return $this->redirectToRoute('group_create');
    }
}