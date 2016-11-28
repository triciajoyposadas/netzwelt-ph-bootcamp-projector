<?php

namespace AppBundle\Service;

use AppBundle\Entity\Project;
use AppBundle\Entity\Person;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ProjectService{

	protected $em;

	public function __construct(EntityManager $em){
		$this->em = $em;
	}

	public function getProjects(){
		return $this->em->getRepository('AppBundle:Project')->findAll();
	}

	public function getProject($project_id, $serialize_format=null){
		
		$project = $this->em->getRepository('AppBundle:Project')->find($project_id);

		if($serialize_format){
			$encoders = array(new XmlEncoder(), new JsonEncoder());
			$normalizer = new ObjectNormalizer();

			$normalizer->setCircularReferenceHandler(function ($object) {
				return $object->getId();
			});

			$serializer = new Serializer(array($normalizer), $encoders);

			$project = $serializer->serialize($project, 'json');
		}

		return $project;
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

	public function getProjectUnassignedPersons($project_id, $serialize_format=null){

		$rsm = new ResultSetMapping();

		$rsm->addEntityResult('AppBundle\Entity\Person', 'person');
		$rsm->addFieldResult('person', 'id', 'id');
		$rsm->addFieldResult('person', 'last_name', 'last_name');
		$rsm->addFieldResult('person', 'first_name', 'first_name');

		$query = $this->em->createNativeQuery(
			'SELECT person.id, person.last_name, person.first_name FROM person
			LEFT JOIN project_assignment pa ON pa.project_id = ?
			AND person.id = pa.person_id
			WHERE pa.person_id is NULL',
			$rsm);
		
		$query->setParameter(1, $project_id);

		$unassigned_persons = $query->getResult();

		if($serialize_format){
			$encoders = array(new XmlEncoder(), new JsonEncoder());
			$normalizer = new ObjectNormalizer();

			$normalizer->setCircularReferenceHandler(function ($object) {
				return $object->getId();
			});

			$serializer = new Serializer(array($normalizer), $encoders);

			$unassigned_persons = $serializer->serialize($unassigned_persons, 'json');
		}
		return $unassigned_persons;
	}

	public function assignPerson($project_id, $person_id){

		$project = $this->em->getRepository('AppBundle:Project')->find($project_id);
		$person = $this->em->getRepository('AppBundle:Person')->find($person_id);

		if($person && $project)
			$project->addPerson($person);

		$this->em->flush();
	}

	public function unassignPerson($project_id, $person_id){

		$project = $this->em->getRepository('AppBundle:Project')->find($project_id);
		$person = $this->em->getRepository('AppBundle:Person')->find($person_id);

		if($person && $project)
			$project->removePerson($person);

		$this->em->flush();
	}
}