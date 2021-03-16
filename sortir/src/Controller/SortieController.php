<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SortieController
 * @Route ("/outing", name="outing_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/new", name="new")
     */
    public function new(): Response
    {
        return $this->render('outing/index.html.twig', [

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
     * @Route ("publish/($id)", name="publish")
     */
    public function publish($id): Response
    {
        return $this->render('outing/publish.html.twig',[

        ]);
    }

}
