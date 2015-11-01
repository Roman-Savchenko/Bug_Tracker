<?php

namespace Acme\BugBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\BugBundle\Entity\Project;
use Acme\BugBundle\Form\Type;

class ProjectController extends Controller
{
    /**
     * @Route("/project", name="project")
     */
    public function projectAction()
    {
        return $this->render('AcmeBugBundle:Project:project_activity.html.twig');

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/project/{page}", name="project_create")
     */
    public function project_createAction($page)
    {
        $user = new Project();
        $form = $this->createForm('form_project_registration', $user);
        return $this->render('AcmeBugBundle:Project:create_project.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/project/{page}", name="project_edit")
     */
    public function project_editAction($page)
    {

        return $this->render('AcmeBugBundle:Project:create_project.html.twig');
    }
}