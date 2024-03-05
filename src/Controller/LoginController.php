<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'une instance de l'entité User
        $user = new User();
        
        // Création du formulaire de connexion
        $form = $this->createForm(LoginType::class, $user);
        
        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $formData = $form->getData();

            // Récupération de l'email et du mot de passe soumis
            $email = $formData->getEmail();
            $password = $formData->getPassword();

            // Recherche de l'utilisateur dans la base de données par email
            $userRepository = $entityManager->getRepository(User::class);
            $existingUser = $userRepository->findOneBy(['email' => $email]);

            if ($existingUser) {
                // Vérification du mot de passe
                if (password_verify($password, $existingUser->getPassword())) {
                    // Authentification réussie, redirection vers une page sécurisée
                    return $this->redirectToRoute('app_users_index'); // Remplacez 'accueil' par le nom de votre route sécurisée
                } else {
                    // Mot de passe incorrect
                    $this->addFlash('error', 'Mot de passe incorrect');
                }
            } else {
                // Utilisateur non trouvé
                $this->addFlash('error', 'Utilisateur non trouvé');
            }
        }

        // Affichage du formulaire de connexion
        return $this->render('login/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}