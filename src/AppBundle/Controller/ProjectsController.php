<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\ControllerInterface\PersonAuthenticatedController;

class ProjectsController extends Controller implements PersonAuthenticatedController{
	
	/**
	 * @Route("/projects/", name="get_project_list")
	 * @Method({"GET"})
	 */
	public function getProjectList(){

		$project_svc = $this->get('app.project_svc');
		$projects = $project_svc->getProjects();

		return $this->render('projector/project/project_list.html.twig', compact('projects'));
	}

	/**
	 * @Route("/projects/create/", name="create_project")
	 * @Method({"GET", "POST"})
	 */
	public function createProject(Request $request){
		
		$form = $this->createFormBuilder()
			->add('code', TextType::class)
			->add('name', TextType::class)
			->add('budget', NumberType::class, array('scale'=> 4))
			->add('remarks', TextareaType::class)
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			
			$data = $form->getData();

			$project_svc = $this->get('app.project_svc');
			$project_svc->addProject(
				$data['code'],
				$data['name'],
				$data['budget'],
				$data['remarks']
			);

			$this->addFlash(
				'success',
				'The project was successfully created.'
			);

			return $this->redirectToRoute('get_project_list');
		}

		$create_project_form_view = $form->createView();

		return $this->render('projector/project/create_project.html.twig', compact('create_project_form_view'));
	}

	/**
	 * @Route("/projects/assignments/{project_id}", name="get_project_assignments")
	 * @Method({"GET"})
	 */
	public function getProjectAssignments($project_id){

		$project_svc = $this->get('app.project_svc');

		$project = $project_svc->getProject($project_id, $serialize_format='json');

		$unassigned_persons = $project_svc->getProjectUnassignedPersons($project_id, $serialize_format='json');

		return $this->render('projector/project/project_assignments.html.twig', compact('project', 'unassigned_persons'));
	}

	/**
	 * @Route("/projects/assign/", name="assign_person")
	 * @Method({"POST"})
	 */
	public function assignPerson(Request $request){

		$data = json_decode($request->getContent(), true);

		$project_id = $data['project_id'];
		$person_id = $data['person_id'];

		$project_svc = $this->get('app.project_svc');
		$project_svc->assignPerson($project_id, $person_id);

		return new Response();
	}

	/**
	 * @Route("/projects/unassign/", name="unassign_person")
	 * @Method({"POST"})
	 */
	public function unassignPerson(Request $request){
		
		$data = json_decode($request->getContent(), true);

		$project_id = $data['project_id'];
		$person_id = $data['person_id'];

		$project_svc = $this->get('app.project_svc');
		$project_svc->unassignPerson($project_id, $person_id);

		return new Response();
	}
}