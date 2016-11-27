<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjectsController extends Controller{
	
	/**
	 * @Route("/projects/")
	 */
	public function projectList(){
		
		$projects = array(
			array(
				'name' => 'Project 1',
				'budget' => '50'
			),
			array(
				'name' => 'Project 2',
				'budget' => '35'
			)
		);

		return $this->render('projector/project/project_list.html.twig', compact('projects'));
	}

	/**
	 * @Route("/projects/create/")
	 */
	public function createProject(){
		
		return $this->render('projector/project/create_project.html.twig');
	}

	/**
	 * @Route("/projects/assignments/")
	 */
	public function projectAssignments(){
		
		$project = array(
			'name' => 'Smartforms'
		);

		$assigned_persons = array(
			array(
				'last_name' => 'Smith',
				'first_name' => 'Jack'
			),
			array(
				'last_name' => 'Cole',
				'first_name' => 'Jill'
			)
		);

		$persons = array(
			array(
				'id' => 1,
				'last_name' => 'Rodriguez',
				'first_name' => 'Jajoy'
			),
			array(
				'id' => 2,
				'last_name' => 'Merill',
				'first_name' => 'Hans'
			)	
		);

		return $this->render('projector/project/project_assignments.html.twig', compact('project', 'assigned_persons', 'persons'));
	}

}