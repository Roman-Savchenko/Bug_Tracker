<?php

namespace Acme\BugBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\BugBundle\Entity\Issue;
use Acme\BugBundle\Entity\User;
use Acme\BugBundle\Form\Type;
use Acme\BugBundle\Repository;
use Acme\BugBundle\Entity\Helper;

class IssueController extends Controller
{
    /**
     * @Route("/issue", name="new_issue")
     */
    public function issueAction(Request $request)
    {
        $issue = new Issue();
        $em = $this->getDoctrine()->getManager();
        $issues = $em->getRepository('AcmeBugBundle:Issue')
            ->findAll();

        return $this->render('AcmeBugBundle:Issue:issue.html.twig', array(
            'issues' => $issues
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/issue_create", name="issue_create")
     */
    public function issue_createAction(Request $request)
    {
        $issue = new Issue();
        $helper = new Helper();
        $form = $this->createForm('form_issue_registration', $issue);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $issue = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($issue);
                $code = $helper->getcode($issue);
                $issue->setCode($code);
                $update = $issue->getCreated();
                $issue->setUpdated($update);
                $em->flush();

                return $this->redirectToRoute('new_issue');
            }
        }
        return $this->render('AcmeBugBundle:Issue:create_issue.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/issue_edit", name="issue_edit")
     */
    public function issue_editAction()
    {
            $issue = new Issue();
            $form = $this->createForm('form_issue_registration', $issue);
            return $this->render('AcmeBugBundle:Issue:create_issue.html.twig', array(
                'form' => $form->createView(),
            ));

    }

    /**
     * @Route("/issue/{code}", name="issue_code")
     */
    public function issue_codeAction($code)
    {
        $em = $this->getDoctrine()->getManager();
        $issue = $em
            ->getRepository('AcmeBugBundle:Issue')
            ->findOneBy(array('code' => $code));

        return $this->render('AcmeBugBundle:Issue:issue_page.html.twig',array(
            'issue'=>$issue
        ));
    }

}