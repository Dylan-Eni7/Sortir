<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin")
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
     * @Route ("/unactive/{$id}", name="unactive")
     */
    public function unactive($id): Response
    {
        return $this->render('admin/unactive.html.twig',[

        ]);
    }
    /**
     * @Route ("/delete/{$id}", name="delete")
     */
    public function delete($id): Response
    {
        return $this->render('admin/delete.html.twig',[

        ]);
    }
}
