<?php

namespace Acme\BugBundle\Services;

use Acme\BugBundle\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Registry;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class IssueEditStatusContainer
{
    /** @var FormFactory */
    protected $FormFactory;

    /** @var Registry */
    protected $doctrine;

    /** @var Comment */
    protected $comment;

    /**
     * @param FormFactory $FormFactory
     * @param Registry $doctrine
     */
    public function __construct(FormFactory $FormFactory, Registry $doctrine)
    {
        $this->FormFactory = $FormFactory;
        $this->doctrine = $doctrine;
    }

    /**
     * @param Request $request
     * @param $issue
     * @return bool|Form|FormInterface
     */
    public function Issueeditstatus( Request $request, $issue)
    {
        $form = $this->FormFactory->create('form_issue_registration', $issue);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $issue = $form->getData();
                $em = $this->doctrine->getManager();
                $em->persist($issue);
                $em->flush();

                return true;
            }
        }

        return $form;
    }
}