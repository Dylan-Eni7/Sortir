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
        $siteRepository = $entityManager->getRepository(Site::class);
        $sites = $siteRepository->findAll();

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $site = new Site();
        $siteForm = $this->createForm(SiteType::class, $site);
        $siteForm->handleRequest($request);

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
        $id = $request->query->get("id");
        $site = $entityManager->find(Site::class, $id);

        $nomSite = $request->query->get("nomSite");

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
        $site = $siteRepository->find($id);
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
        $villeRepository = $entityManager->getRepository(Ville::class);
        $villes = $villeRepository->findAll();

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {

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
        $id = $request->query->get("id");
        $ville = $entityManager->find(Ville::class, $id);

        $nomVille = $request->query->get("nomVille");
        $codePostal = $request->query->getInt("codePostal");

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
        $ville = $villeRepository->find($id);

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
