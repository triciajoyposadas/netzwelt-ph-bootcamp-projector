<?php

namespace AppBundle\EventListener;

use AppBundle\ControllerInterface\PersonAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class PersonAuthenticationListener{

    protected $session;

    public function __construct(Session $session, Router $router){
        $this->session = $session;
        $this->router = $router;
    }

    public function onKernelController(FilterControllerEvent $event){

        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof PersonAuthenticatedController) {
            
            $event->getRequest()->attributes->set('attempt_person_auth', true);

            if ($this->session->has('username')){
                
                $event->getRequest()->attributes->set('person_auth_success', true);
            }
        }
    }

    public function onKernelResponse(FilterResponseEvent $event){

        if ($event->getRequest()->attributes->has('attempt_person_auth')){

            if (!$event->getRequest()->attributes->has('person_auth_success')){
                
                $url = $this->router->generate('sign_in');
                
                $response = new RedirectResponse($url);

                $event->setResponse($response);
                return;    
            }
        }
    }
}