<?php

namespace AppBundle\Service;

use AppBundle\Entity\Person;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class PersonService{

	protected $em;

	public function __construct(EntityManager $em){
		$this->em = $em;
	}

	public function getPersons(){
		return $this->em->getRepository('AppBundle:Person')->findAll();
	}

	public function addPerson($last_name, $first_name, $username, $password){
		$person = new Person();

	    $person->setLastName($last_name);
	    $person->setFirstName($first_name);
	    $person->setUsername($username);
	    $person->setPassword($password);

	    $this->em->persist($person);

	    $this->em->flush();
	}

	public function attemptSignIn($username, $password){
		$person = $this->em->getRepository('AppBundle:Person')->findOneBy(
			array('username' => $username, 'password' => $password)
		);

		if($person){
			
			$session = $this->getRequest()->getSession();

			$session->set('username', $person->getUsername());
			$session->set('first_name', $person->getFirstname());

			$session->save();

			return true;
		}
		
		return false;
	}

	public function attemptSignOut(){

		$session = $this->session;

		$session->invalidate();

		$session->save();
	}
}