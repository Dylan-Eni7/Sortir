<?php

namespace App\Controller;

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

            $this->addFlash('success', 'Votre sortie a bien été créer !');
            return $this->redirectToRoute(
                'outing_list'
            );
        }

        return $this->render('outing/new.html.twig', [
            'sortieFormView' => $sortieForm->createView(),
        ]);
    }

    /**
     * @Route ("/cancel/($id)", name="cancel")
     */
    public function cancel($id): Response
    {
        return $this->render('outing/cancel.html.twig',[

        ]);
    }
    /**
     * @Route ("/modify/($id)", name="modify")
     */
    public function modify($id): Response
    {
        return $this->render('outing/modify.html.twig',[

        ]);
    }
    /**
     * @Route ("/detail/($id)", name="detail")
     */
    public function detail($id): Response
    {
        return $this->render('outing/detail.html.twig',[

        ]);
    }
    /**
     * @Route ("/publish/($id)", name="publish")
     */
    public function publish($id): Response
    {
        return $this->render('outing/publish.html.twig',[

        ]);
    }

}
