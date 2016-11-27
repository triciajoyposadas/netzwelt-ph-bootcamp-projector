<?php

namespace AppBundle\Service;

use AppBundle\Entity\Person;
use Doctrine\ORM\EntityManager;

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

	public function attemptSignIn(){

	}

	public function attempSignout(){

	}
}