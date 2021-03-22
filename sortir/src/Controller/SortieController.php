<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
    public function modify($id, EntityManagerInterface $entityManager): Response
    {
        $sortie = $entityManager->find(Sortie::class, $id);

        $siteRepository = $entityManager->getRepository(Site::class);
        $lieuRepository = $entityManager->getRepository(Lieu::class);
        $villeRepository = $entityManager->getRepository(Ville::class);

        $sites = $siteRepository->findAll();
        $lieux = $lieuRepository->findAll();
        $villes = $villeRepository->findAll();

        $site = $sortie->getSite();
        $lieu = $sortie->getLieu();
        $ville = $lieu->getVille();


        return $this->render('outing/modify.html.twig',[
            'sortie' => $sortie,

            'site' => $site,
            'lieu' => $lieu,
            'ville' => $ville,

            'sites' => $sites,
            'lieux' => $lieux,
            'villes' => $villes
        ]);
    }

    /**
     * @Route ("/modify/validate/{id}", name="modify_validate")
     */
    public function modify_validate($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $lieuRepository = $entityManager->getRepository(Lieu::class);

        $nomSortie = $_POST["nomSortie"];
        $villeOrganisatrice = $_POST["villeOrganisatrice"];
        $dateSortie = $_POST["dateSortie"];

        $lieuSortie = $_POST["lieuSortie"];
        $lieuSortie = $lieuRepository->findOneBy(array('rue' => $lieuSortie));
        $lieuSortie = $lieuSortie->getId();

        $dateLimite = $_POST["dateLimite"];
        $nbPlaces = $_POST["nbPlaces"];
        $duree = $_POST["duree"];
        $description = $_POST["description"];
        if (isset($_POST["button_1"])) {
        $etat = "En création";
        }
        if (isset($_POST["button_2"])){
        $etat = "Ouvert";
        }

        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortieRepository->modify($id, $nomSortie, $villeOrganisatrice, $dateSortie, $lieuSortie, $dateLimite, $nbPlaces,
            $duree, $description, $etat);
        $sortie = $entityManager->find(Sortie::class, $id);

        return $this->redirectToRoute("outing_list");
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
