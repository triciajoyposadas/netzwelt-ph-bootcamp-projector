<?php

namespace AppBundle\Service;

use AppBundle\Entity\Project;
use Doctrine\ORM\EntityManager;

class ProjectService{

	protected $em;

	public function __construct(EntityManager $em){
		$this->em = $em;
	}

	public function getProjects(){
		return $this->em->getRepository('AppBundle:Project')->findAll();
	}

	public function getProject($project_id){
		return $this->em->getRepository('AppBundle:Project')->find($project_id);;
	}

	public function addProject($code, $name, $budget, $remarks){
		$project = new Project();

	    $project->setCode($code);
	    $project->setName($name);
	    $project->setBudget($budget);
	    $project->setRemarks($remarks);

	    $this->em->persist($project);

	    $this->em->flush();
	}

	public function getProjectUnassignedPersons($project_id){
		return $this->em->getRepository('AppBundle:Person')->findAll();
	}
}