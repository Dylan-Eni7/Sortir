<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/participant", name="participant_")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route ("/register/($id.user)/($id.outing)", name="register")
     */
    public function register(): Response
    {
        return $this->render('participant/register.html.twig', [

        ]);
    }
    /**
     * @Route ("/withdraw/($id.user)/($id.outing)", name="withdraw")
     */
    public function withdraw(): Response
    {
        return $this->render('participant/withdraw.html.twig',[

        ]);
    }
}
