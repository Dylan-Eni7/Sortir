<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;



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
    public function profile($id, UserInterface $user,
                            EntityManagerInterface $entityManager,
                            Request $request): Response
    {
        $profil = new Participant();
        $profilForm = $this->createForm(ProfilType::class, $profil);
        $profilForm->handleRequest($request);

        if ($profilForm->isSubmitted() && $profilForm->isValid()) {
            $entityManager->persist($profil);
            $entityManager->flush();

            $this->addFlash('success', 'Votre sortie a bien été créer !');
            return $this->redirectToRoute(
                'user_profile',
                ['id' => $profil->getId()]
            );
        }

        {
            return $this->render('participant/profile.html.twig', [
                'profilFormView' => $profilForm->createView(),
            ]);
        }
    }
}
