<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class UserController
 * @package App\Controller
 * @Route ("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/modify/($id)", name="modify")
     */
    public function modify($id): Response
    {
        return $this->render('user/modify.html.twig', [

        ]);
    }

    /**
     * @Route ("/profile/{id}", name="profile")
     */
    public function profile($id): Response
    {
        return $this->render('participant/profile.html.twig', [

        ]);
    }

}
