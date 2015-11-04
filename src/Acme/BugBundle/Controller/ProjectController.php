<?php

namespace Acme\BugBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\BugBundle\Entity\Project;
use Acme\BugBundle\Form\Type;
use Acme\BugBundle\Repository;

class ProjectController extends Controller
{
    /**
     * @Route("/project", name="project")
     */
    public function projectAction()
    {
        $projects = $this->getDoctrine()
            ->getRepository('AcmeBugBundle:Project')
            ->findAll();
        $labels = null;
        $label_project = null;
        $ids = null;
        $titles = null;

        foreach($projects as $project)
        {
            $label_project[] = $project->getLabel();
            $labels[] = explode(' ',ucwords($project->getLabel()));
            $ids[] .= $project->getId();
            foreach($labels as $label)
            {
                $i = 0;
                $titles .= $i;
               foreach($label as $value)
               {
                   $titles .= substr($value,0,1);
               }
            }
        }
        $render = array_combine(array_unique(explode('0',ltrim($titles,"0"))),$ids);

        return $this->render('AcmeBugBundle:Project:project_activity.html.twig', array(
            'titles'=>$label_project

        ));

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/project/{page}", name="project_create")
     */
    public function project_createAction(Request $request)
    {

        $project = new Project();

        $form = $this->createForm('form_project_registration', $project);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $project = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $em->flush();

                return $this->redirectToRoute('project');
            }
        }
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