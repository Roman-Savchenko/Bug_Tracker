<?php

namespace Acme\BugBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class IssueRegistrationContainer
{
    /** @var FormFactory */
    protected $FormFactory;

    /** @var Registry */
    protected $doctrine;

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
     * @return Form|FormInterface
     */
    public function IssueRegistration(Request $request)
    {
        $form = $this->FormFactory->create('form_issue_registration', null);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $issue = $form->getData();
                $em = $this->doctrine->getEntityManager();
                $em->persist($issue);
                $update = $issue->getCreated();
                $issue->setUpdated($update);
                $em->flush();

                return true;
            }
        }

        return $form;
    }
}