<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\SortieType;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
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

        //Je crée un tableau contenant toutes les sorties de la BDD
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sorties = $sortieRepository->findAll();

        $date = new \DateTime('now');

        $participant = $this->getUser();



        //J'envoie mon tableau de Sorties sur la page twig

        return $this->render('outing/index.html.twig',
            [
                'sorties' => $sorties,
                'date' => $date,
                'participant' => $participant
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
        //Si je ne suis pas Admin, je refuse l'acces à la page.
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //Je crée une nouvelle sortie.
        $sortie = new Sortie();

        //Je crée le formulaire.
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        //J'assigne l'organisateur à la sortie selon l'utilisateur connecté.
        $sortie->setOrganisateur($this->getUser());

        //Si le formulaire est envoyé et valide
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            //Si je clique sur Enregistrer, j'assigne l'état "En création" à ma sortie.
            if ($sortieForm->get('Enregistrer')->isClicked()) {
                $sortie->setEtat("En création");
            }
            //Si je clique sur Publier, j'assigne l'état "Ouvert" à ma sortie.
            if ($sortieForm->get('Publier')->isClicked()) {
                $sortie->setEtat("Ouvert");
            }

            //J'enregistre ma sortie en BDD
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
        //Je récupère en BDD, la sortie selon l'id envoyé.
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        //Je supprime la sortie correspondante et sauvegarde le changement en BDD.
        $entityManager->remove($sortie);
        $entityManager->flush();

        return $this->redirectToRoute("outing_list");
    }

    /**
     * @Route ("/cancel/{id}", name="cancel")
     */
    public function cancel($id, EntityManagerInterface $entityManager): Response
    {
        //Si je ne suis pas Admin, je refuse l'acces à la page.
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //Je récupère en BDD, la sortie selon l'id envoyé.
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        //Je récupère le Site/Lieu/Ville de la sortie que je modifie.
        $site = $sortie->getSite();
        $lieu = $sortie->getLieu();
        $ville = $lieu->getVille();

        return $this->render('outing/cancel.html.twig', [
            'sortie' => $sortie,

            'site' => $site,
            'lieu' => $lieu,
            'ville' => $ville
        ]);
    }

    /**
     * @Route ("/cancel/validate/{id}", name="cancel_validate")
     */
    public function cancelValidate($id, EntityManagerInterface $entityManager): Response
    {
        //Si je ne suis pas Admin, je refuse l'acces à la page.
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //Je récupère toutes les infos envoyés dans l'URL depuis mon formulaire.
        $motif = $_POST["motif"];

        //Je récupère en BDD, la sortie selon l'id envoyé.
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        $sortie->setEtat("Annulé");
        $sortie->setInfosSortie($motif);

        //Je sauvegarde en BDD.
        $entityManager->flush();

        return $this->redirectToRoute("outing_list");
    }

    /**
     * @Route ("/modify/{id}", name="modify")
     */
    public function modify($id, EntityManagerInterface $entityManager): Response
    {
        //Je recherche la sortie en BDD correspondante selon l'id envoyé.
        $sortie = $entityManager->find(Sortie::class, $id);

        //Je récupère les repository afin de manipuler les Site/Lieu/Ville.
        $siteRepository = $entityManager->getRepository(Site::class);
        $lieuRepository = $entityManager->getRepository(Lieu::class);
        $villeRepository = $entityManager->getRepository(Ville::class);

        //Je récupère les Sites/Lieux/Villes existant dans la BDD et les stockent dans des tableaux.
        $sites = $siteRepository->findAll();
        $lieux = $lieuRepository->findAll();
        $villes = $villeRepository->findAll();

        //Je récupère le Site/Lieu/Ville de la sortie que je modifie.
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
    public function modify_validate($id, Request $request, EntityManagerInterface $entityManager,
                                    LieuRepository $lieuRepository, SiteRepository $siteRepository): Response
    {
        //Je récupère toutes les infos envoyés dans l'URL depuis mon formulaire.
        $nomSortie = $_POST["nomSortie"];

        //Je récupère la ville organisatrice en BDD selon l'id envoyé dans l'URL.
        $villeOrganisatrice = $siteRepository->findOneBy(array('id' => $_POST["villeOrganisatrice"]));

        $dateSortie = $_POST["dateSortie"];

        //Je récupère le Lieu en BDD selon le nom de rue envoyé dans l'URL.
        $lieuSortie = $lieuRepository->findOneBy(array('rue' => $_POST["lieuSortie"]));

        $dateLimite = $_POST["dateLimite"];
        $nbPlaces = $_POST["nbPlaces"];
        $duree = $_POST["duree"];
        $description = $_POST["description"];

        //Je vérifie le button appuyé dans le formulaire
        //Si le button_1 a été appuyé, j'assigne l'état "En création" à ma sortie.
        if (isset($_POST["button_1"])) {
        $etat = "En création";
        }
        //Si le button_2 a été appuyé, j'assigne l'état "Ouvert" à ma sortie.
        if (isset($_POST["button_2"])){
        $etat = "Ouvert";
        }

        //Je récupère en BDD la sortie correspondante à l'id envoyé.
        $sortie = $entityManager->find(Sortie::class, $id);

        //J'assigne les valeurs aux paramètres de ma sortie.
        $sortie->setNom($nomSortie);
        $sortie->setSite($villeOrganisatrice);
        $sortie->setDateHeureDebut(new \DateTime($dateSortie));
        $sortie->setLieu($lieuSortie);
        $sortie->setDateLimiteInscription(new \DateTime($dateLimite));
        $sortie->setNbInscriptionsMax($nbPlaces);
        $sortie->setDuree($duree);
        $sortie->setInfosSortie($description);
        $sortie->setEtat($etat);

        //Je sauvegarde en BDD.
        $entityManager->flush();

        return $this->redirectToRoute("outing_list");
    }

    /**
     * @Route ("/detail/{id}", name="detail")
     */
    public function detail($id, EntityManagerInterface $entityManager): Response
    {
        //Je récupère la sortie en BDD correspondant à l'id envoyé.
        $sortie = $entityManager->find(Sortie::class, $id);

        //Je récupère le Site/Lieu/Ville associé à ma sortie.
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
        //Je récupère la sortie en BDD selon l'id envoyé.
        $sortie = $entityManager->find(Sortie::class, $id);

        //J'assigne l'état "Ouvert" à la sortie.
        $sortie->setEtat("Ouvert");

        //Je sauvegarde l'état en BDD.
        $entityManager->flush();

        return $this->redirectToRoute("outing_list");
    }

}
