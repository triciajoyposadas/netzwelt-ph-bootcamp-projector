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

class ProjectsController extends Controller{
	
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
			->add('budget', NumberType::class)
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
		
		$project = $project_svc->getProject($project_id);

		$unassigned_persons = $project_svc->getProjectUnassignedPersons($project_id);

		return $this->render('projector/project/project_assignments.html.twig', compact('project', 'unassigned_persons'));
	}

}