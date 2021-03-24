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
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request,
                         UserPasswordEncoderInterface $passwordEncoder,
                         EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $profilform = $this->createForm(ProfilType::class, $user);
        $profilform->handleRequest($request);
        if ($profilform->isSubmitted() && $profilform->isValid()) {
            //hashage du password

//                $hashed = $passwordEncoder->encodePassword($user, $user->getPassword());
//                $user->setPassword($hashed);
//                $entityManager->persist($user);
//                $entityManager->flush();

                $user->setPassword(
                        $passwordEncoder->encodePassword(
                        $user,
                        $profilform->get('password')->getData()
                    )
                );

            $entityManager->flush($user);
            $this->addFlash('success', '✔ Profil modifié ! ');
            return $this->redirectToRoute("user_profile", [

            ]);

        }

        return $this->render('participant/edit.html.twig', [
            'profilFormView' => $profilform->createView(),
            'user' => $user
        ]);
    }



    /**
     * @Route ("/profile/{id}", name="profile")
     */
    public function profile($id, EntityManagerInterface $entityManager): Response

    {

        $user = $entityManager->find(Participant::class, $id);
        if ($user == null) {
            throw $this->createNotFoundException("L'utilisateur est absent dans la base de données. Essayez un autre ID !");
        }

        return $this->render("participant/profile.html.twig", [
            'controller_name' => 'ParticipantController',
            'participant' => $user
        ]);
    }
}