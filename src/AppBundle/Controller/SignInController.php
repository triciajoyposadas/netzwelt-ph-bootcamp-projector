<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class SignInController extends Controller{
	
	/**
	 * @Route("/signin", name="sign_in")
	 * @Method({"GET", "POST"})
	 */
	public function signIn(Request $request){
		
		$form = $this->createFormBuilder()
			->add('username', EmailType::class, array('attr' => array('minlength' => 5, 'maxlength' => 200)))
			->add('password', PasswordType::class, array('attr' => array('minlength' => 7, 'maxlength' => 11)))
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			
			$data = $form->getData();

			$person_svc = $this->get('app.person_svc');
			
			if($person_svc->attemptSignIn($data['username'], $data['password']))
				return $this->redirectToRoute('get_project_list');
			else{
				$this->addFlash(
					'error',
					'The username or password you entered does not exist.'
				);
				return $this->redirectToRoute('sign_in');
			}
		}
		
		$sign_in_form_view = $form->createView();

		return $this->render('projector/sign_in.html.twig', compact('sign_in_form_view'));
	}

	/**
	 * @Route("/signout", name="sign_out")
	 * @Method({"GET"})
	 */
	public function signOut(){
		
		$person_svc = $this->get('app.person_svc');
		$person_svc->attemptSignOut();

		return $this->redirectToRoute('sign_in');
	}
}