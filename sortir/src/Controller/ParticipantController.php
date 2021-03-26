<?php

namespace App\Controller;

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
        //Je récupère la sortie en BDD selon l'id envoyé.
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        //Je compte le nombre de participant actuellement inscrit à la sortie.
        $nbParticipant = count($sortie->getParticipant());

        //S'il reste une place,
        if ($nbParticipant < $sortie->getNbInscriptionsMax()){

            //Je récupère l'utilisateur,
            $participant=$this->getUser();

            //Je l'ajoute à la sortie correspondante,
            $participant->addSorty($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription accepté !');
        } else {
            $this->addFlash('error', "Il n'y a plus de place pour cette sortie !");
        }
        return $this->redirectToRoute("outing_list");
    }

    /**
     * @Route ("/withdraw/{id}", name="withdraw")
     */
    public function withdraw($id, EntityManagerInterface $entityManager): Response
    {
        //Je récupère la sortie en BDD selon l'id envoyé.
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        //Je récupère l'utilisateur.
        $participant=$this->getUser();


        //Je retire l'utilisateur de la sortie.
        $participant->removeSorty($sortie);
        $entityManager->flush();

        $this->addFlash('success', 'Vous êtes bien désinscrit de la sortie !');
        return $this->redirectToRoute("outing_list");
    }
}
