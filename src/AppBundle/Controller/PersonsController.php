<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PersonsController extends Controller{
	
	/**
	 * @Route("/persons/create/")
	 */
	public function createPerson(){
		
		return $this->render('projector/person/create_person.html.twig');
	}
}