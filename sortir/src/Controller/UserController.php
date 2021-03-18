<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function profile($id,
                            EntityManagerInterface $entityManager,
                            Request $request,
                            UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $entityManager->getRepository(Participant::class)->find($this->getUser()->getId());
        $profilform = $this->createForm(ProfilType::class, $user);
        $profilform->handleRequest($request);
        if ($profilform->isSubmitted() && $profilform->isValid()) {

            $hashed = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute("user_profile", ["id"=>$user->getId()]);
        }
        return $this->render("participant/profile.html.twig", [
            'profilFormView' => $profilform->createView(),
            'user' => $user
        ]);
    }
}
