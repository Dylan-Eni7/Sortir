<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/participant", name="participant_")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route ("/register/{id}", name="register")
     */
    public function register($id, EntityManagerInterface $entityManager): Response
    {
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        $participant=$this->getUser();

        $participant->addSorty($sortie);
        $entityManager->flush();
        return $this->redirectToRoute("outing_list");
    }
    /**
     * @Route ("/withdraw/{id}", name="withdraw")
     */
    public function withdraw($id, EntityManagerInterface $entityManager): Response
    {
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        $participant=$this->getUser();

        $participant->removeSorty($sortie);
        $entityManager->flush();
        return $this->redirectToRoute("outing_list");
    }
}
