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
    //gestion du formulaire de modification d'utilisateur
    /**
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request,
                         UserPasswordEncoderInterface $passwordEncoder,
                         EntityManagerInterface $entityManager): Response
    {
        //récuperation de l'utilisateur
        $user = $this->getUser();
        //création du formulaire de modification
        $profilform = $this->createForm(ProfilType::class, $user);
        $profilform->handleRequest($request);
        //si profilfrom est validé et valide
        if ($profilform->isSubmitted() && $profilform->isValid()) {
            //hashage du password
                $user->setPassword(
                        $passwordEncoder->encodePassword(
                        $user,
                        $profilform->get('password')->getData()
                    )
                );
            //puis envoie du password hashé et les informations de l'utilisateur en BDD
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


    //gestion du profil des autres participants
    /**
     * @Route ("/profile/{id}", name="profile")
     */
    public function profile($id, EntityManagerInterface $entityManager): Response

    {
        //recherche un utilisateur en fonction de son id
        $user = $entityManager->find(Participant::class, $id);
        if ($user == null) {
            //si null, renvoie un message d'erreur
            throw $this->createNotFoundException("L'utilisateur est absent dans la base de données. Essayez un autre ID !");
        }

        return $this->render("participant/profile.html.twig", [
            'controller_name' => 'ParticipantController',
            'participant' => $user
        ]);
    }
}