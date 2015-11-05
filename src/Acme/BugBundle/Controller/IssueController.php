<?php

namespace Acme\BugBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\BugBundle\Entity\Issue;
use Acme\BugBundle\Form\Type;

class IssueController extends Controller
{
    /**
     * @Route("/issue", name="new_issue")
     */
    public function issueAction()
    {
     return $this->render('AcmeBugBundle:Issue:issue.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/issue/{page}", name="issue_create")
     */
    public function issue_createAction($page)
    {
        $user = new Issue();
        $form = $this->createForm('form_issue_registration', $user);
        return $this->render('AcmeBugBundle:Issue:create_issue.html.twig', array(
            'form' => $form->createView(),
        ));

    }

}