<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SignInController extends Controller{
	
	/**
	 * @Route("/signin")
	 */
	public function signInAction(){
		
		return $this->render('projector/sign_in.html.twig');
	}

	/**
	 * @Route("/signout")
	 */
	public function signOutAction(){
		
		return 0;
	}
}