<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\Ville;
use App\Form\SiteType;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route ("/create", name="create")
     */
    public function create(): Response
    {
        return $this->render('admin/create.html.twig', [

        ]);
    }

    /**
     * @Route ("/create/site", name="create_site")
     */
    public function createSite(
        EntityManagerInterface $entityManager,
        Request $request): Response
    {
        //Si je ne suis pas admin je ne peux pas accéder a la page.
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //Je créer le repository afin de récupérer un tableau contenant tout les sites existant.
        $siteRepository = $entityManager->getRepository(Site::class);
        $sites = $siteRepository->findAll();

        $site = new Site();

        //Je créer un formulaire.
        $siteForm = $this->createForm(SiteType::class, $site);
        $siteForm->handleRequest($request);

        //Si le formaulaire est valide, j'ajoute le nouveau Site en BDD.
        if ($siteForm->isSubmitted() && $siteForm->isValid()) {

            $entityManager->persist($site);
            $entityManager->flush();

            $this->addFlash('success', 'Votre site a bien été ajouté !');
            return $this->redirectToRoute(
                'admin_create_site'
            );
        }

        return $this->render('admin/newSite.html.twig', [
            'sites' => $sites,
            'siteFormView' => $siteForm->createView(),
        ]);
    }

    /**
     * @Route ("/modify/site", name="modify_site")
     */
    public function modifySite(EntityManagerInterface $entityManager, Request $request): Response
    {
        //Je récupère l'id et le nom du site qui sont en paramètres dans l'URL
        $id = $request->query->get("id");
        $nomSite = $request->query->get("nomSite");

        //Je recherche le Site qui correspond à l'id donné
        $site = $entityManager->find(Site::class, $id);

        //Je remplace le nom du Site actuel par le nom récupéré puis je sauvegarde le changement en BDD
        $site->setNom(strtoupper($nomSite));
        $entityManager->flush();

        $this->addFlash('success', 'Votre site a bien été mis a jour !');
        return $this->redirectToRoute(
            'admin_create_site'
        );
    }

    /**
     * @Route ("/delete/site/{id}", name="delete_site")
     */
    public function deleteSite($id, EntityManagerInterface $entityManager): Response
    {
        $siteRepository = $entityManager->getRepository(Site::class);

        //Je récupère le Site en BDD selon l'id
        $site = $siteRepository->find($id);

        //Je supprime le Site en BDD et sauvegarde le changement
        $entityManager->remove($site);
        $entityManager->flush();

        $this->addFlash('success', 'Votre site a bien été supprimé !');
        return $this->redirectToRoute("admin_create_site");
    }

    /**
     * @Route ("/create/ville", name="create_ville")
     */
    public function createVille(
        EntityManagerInterface $entityManager,
        Request $request): Response
    {
        //Si je ne suis pas admin, l'acces est refusé
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $villeRepository = $entityManager->getRepository(Ville::class);

        //Je créer un tableau contenant toutes les villes de la BDD.
        $villes = $villeRepository->findAll();

        //Je créer une nouvelle ville ainsi que le formulaire
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            //Si le formulaire est valide
            //Je met chaque première lettre de chaque mot en MAJ et l'assigne en nom de ville
            $ville->setNom(ucwords($villeForm->getData()->getNom(), " "));
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'Votre ville a bien été ajouté !');
            return $this->redirectToRoute(
                'admin_create_ville'
            );
        }

        return $this->render('admin/newVille.html.twig', [
            'villes' => $villes,
            'villeFormView' => $villeForm->createView(),
        ]);
    }

    /**
     * @Route ("/modify/ville", name="modify_ville")
     */
    public function modifyVille(EntityManagerInterface $entityManager, Request $request): Response
    {
        //Je récupère l'id envoyé dans l'URL
        $id = $request->query->get("id");

        //Je récupère la ville correspondante selon l'id
        $ville = $entityManager->find(Ville::class, $id);

        //Je récupère le nom et le code postal envoyé dans l'URL
        $nomVille = $request->query->get("nomVille");
        $codePostal = $request->query->getInt("codePostal");

        //J'assigne le nom et le code postal ç la ville que je modifie
        $ville->setNom(ucwords($nomVille, " "));
        $ville->setCodePostal($codePostal);

        $entityManager->flush();

        $this->addFlash('success', 'Votre ville a bien été mis a jour !');
        return $this->redirectToRoute(
            'admin_create_ville'
        );
    }

    /**
     * @Route ("/delete/ville/{id}", name="delete_ville")
     */
    public function deleteVille($id, EntityManagerInterface $entityManager): Response
    {
        $villeRepository = $entityManager->getRepository(Ville::class);

        //Je récupère la ville en BDD qui correspond a l'id envoyé
        $ville = $villeRepository->find($id);

        //Je supprime la ville
        $entityManager->remove($ville);
        $entityManager->flush();

        $this->addFlash('success', 'Votre ville a bien été supprimé !');
        return $this->redirectToRoute("admin_create_ville");
    }

    /**
     * @Route ("/unactive/{$id}", name="unactive")
     */
    public function unactive($id): Response
    {
        return $this->render('admin/unactive.html.twig',[

        ]);
    }



}
