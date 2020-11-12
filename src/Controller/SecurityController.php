<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", priority="10")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
    	// get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();
	    // last username entered by the user
	    $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render( 'security/login.html.twig', [
	        'last_username' => $lastUsername,
	        'error' => $error
        ]);
    }

	/**
	 * @Route("/logout", name="app_logout", priority="10")
	 */
	public function logout()
	{
	}

	/**
	 * @Route("/api/login", name="app_login_api", priority="10")
	 */
	public function apiLogin()
	{
	}
}
