<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PersonsController extends Controller{
	
	/**
	 * @Route("/persons/create/", name="create_person")
	 * @Method({"GET", "POST"})
	 */
	public function createPerson(Request $request){
		
		$form = $this->createFormBuilder()
			->add('last_name', TextType::class, array('attr' => array('minlength' => 2, 'maxlength' => 50)))
			->add('first_name', TextType::class, array('attr' => array('minlength' => 2, 'maxlength' => 50)))
			->add('username', EmailType::class, array('attr' => array('minlength' => 5, 'maxlength' => 200)))
			->add('password', PasswordType::class, array('attr' => array('minlength' => 7, 'maxlength' => 11)))
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			
			$data = $form->getData();

			$person_svc = $this->get('app.person_svc');
			$person_svc->addPerson(
				$data['last_name'],
				$data['first_name'],
				$data['username'],
				$data['password']
			);

			return $this->redirectToRoute('get_project_list');
		}

		$create_person_form_view = $form->createView();

		return $this->render('projector/person/create_person.html.twig', compact('create_person_form_view'));

	}
}