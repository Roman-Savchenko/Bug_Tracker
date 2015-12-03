<?php

namespace Acme\BugBundle\Controller;

use Acme\BugBundle\Entity\Issue;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\BugBundle\Entity\Project;
use Acme\BugBundle\Form\Type;
use Acme\BugBundle\Repository;
use Acme\BugBundle\Entity\Helper;

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
        return $this->render('AcmeBugBundle:Project:project_activity.html.twig', array(
            'projects'=>$projects

        ));

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/project_create", name="project_create")
     */
    public function project_createAction(Request $request)
    {
        $helper = new Helper();
        $project = new Project();
        $form = $this->createForm('form_project_registration', $project);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $project = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $code = $helper->getcode($project);
                $project->setCode($code);
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
     * @Route("/project_edit", name="project_edit")
     */
    public function project_editAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm('form_project_registration', $project);
        return $this->render('AcmeBugBundle:Project:create_project.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/project/{code}", name="project_code")
     */
    public function project_codeAction($code)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em
            ->getRepository('AcmeBugBundle:Project')
            ->findOneBy(array('code' => $code));

        return $this->render('AcmeBugBundle:Project:project_page.html.twig',array(
            'project'=>$project
        ));
    }
}