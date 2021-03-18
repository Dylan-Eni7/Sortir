<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SortieController
 * @Route ("/outing", name="outing_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function list(EntityManagerInterface $entityManager): Response
    {
        /** @var SortieRepository $sortieRepository */
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sorties = $sortieRepository->findAll();


        return $this->render('outing/index.html.twig',
            [
                'sorties' => $sorties,
            ]
        );
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        $sortie->setOrganisateur($this->getUser());

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            if ($sortieForm->get('Enregistrer')->isClicked()) {
                $sortie->setEtat("En création");
            }
            if ($sortieForm->get('Publier')->isClicked()) {
                $sortie->setEtat("Ouvert");
            }

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Votre sortie a bien été créée !');
            return $this->redirectToRoute(
                'outing_list'
            );
        }

        return $this->render('outing/new.html.twig', [
            'sortieFormView' => $sortieForm->createView(),
        ]);
    }

    /**
     * @Route ("/delete/{id}", name="delete")
     */
    public function delete($id, EntityManagerInterface $entityManager): Response
    {
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);
        $entityManager->remove($sortie);
        $entityManager->flush();
        return $this->redirectToRoute("outing_list");
    }

    /**
     * @Route ("/cancel/{id}", name="cancel")
     */
    public function cancel($id): Response
    {
        return $this->render('outing/cancel.html.twig',[

        ]);
    }

    /**
     * @Route ("/modify/{id}", name="modify")
     */
    public function modify($id): Response
    {
        return $this->render('outing/modify.html.twig',[

        ]);
    }
    /**
     * @Route ("/detail/{id}", name="detail")
     */
    public function detail($id, EntityManagerInterface $entityManager): Response
    {
        $sortie = $entityManager->find(Sortie::class, $id);
        $site = $sortie->getSite();
        $lieu = $sortie->getLieu();
        $ville = $lieu->getVille();

        return $this->render('outing/detail.html.twig',[
            'sortie' => $sortie,
            'lieu' => $lieu,
            'site' => $site,
            'ville' => $ville
        ]);
    }
    /**
     * @Route ("/publish/{id}", name="publish")
     */
    public function publish($id, EntityManagerInterface $entityManager): Response
    {
        $sortie = $entityManager->find(Sortie::class, $id);
        $sortie->setEtat("Ouvert");
        $entityManager->flush();

        return $this->redirectToRoute("outing_list");
    }

}
