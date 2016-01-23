<?php

namespace Acme\BugBundle\Controller;

use Acme\BugBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\BugBundle\Entity\Issue;
use Acme\BugBundle\Entity\User;
use Acme\BugBundle\Form\Type;
use Acme\BugBundle\Repository;
use Symfony\Component\Form\FormInterface;

class IssueController extends Controller
{
    /**
     * @Route("/issue", name="new_issue")
     */
    public function issueAction(Request $request)
    {
        $date = new \DateTime();
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
        $form = $this->container->get('issue.registration')->IssueRegistration($request);

        if ($form instanceof FormInterface) {
            return $this->render('AcmeBugBundle:Issue:create_issue.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        return $this->redirectToRoute('new_issue');
    }

    /**
     * @param Request $request
     * @param $code
     * @return RedirectResponse|Response
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/issue_edit/{code}", name="issue_edit")
     */
    public function issue_editAction($code, Request $request)
    {
        $issue = $this->getDoctrine()
            ->getRepository('AcmeBugBundle:Issue')
            ->findOneByCode($code);
        $form = $this->container->get('issue.edit.status')->Issueeditstatus($request, $issue);

        if ($form instanceof FormInterface) {
            return $this->render('AcmeBugBundle:Issue:edit_status.html.twig', array(
                'form' => $form->createView(),
                'code' =>$code,
            ));
        }
        return $this->redirectToRoute('issue_code', array('code' => $code));
    }

    /**
     * @param $code
     * @return Response
     * @Route("/issue/{code}", name="issue_code")
     */
    public function issue_codeAction($code)
    {
        $em = $this->getDoctrine()->getManager();
        $issue = $em
            ->getRepository('AcmeBugBundle:Issue')
            ->findOneBy(array('code' => $code));
        $comments = $em
            ->getRepository('AcmeBugBundle:Comment')
            ->findByIssue($issue->getId());

        return $this->render('AcmeBugBundle:Issue:issue_page.html.twig',array(
            'issue' => $issue,
            'comments'=> $comments
        ));
    }

    /**
     * @Route("/issue/{code}/comment", name="issue_comment")
     * @param Request $request
     * @return Form|FormInterface
     */
    public function addCommentAction($code,Request $request)
    {
        $userId = $this->getUser();
        $form = $this->container->get('issue.comment')->IssueComment($code, $request, $userId);

        if ($form instanceof FormInterface) {
            return $this->render('AcmeBugBundle:Issue:create_issue_comment.html.twig', array(
                'form' => $form->createView(),
                'code' => $code,
            ));
        }

        return $this->redirectToRoute('issue_code',array('code'=>$code));
    }

}