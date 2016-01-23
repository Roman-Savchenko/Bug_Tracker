<?php

namespace Acme\BugBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class ProjectCreateContainer
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
     * @return bool|Form|FormInterface
     */
    public function Projectcreate (Request $request)
    {
        $form = $this->FormFactory->create('form_project_registration', null);
        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $project = $form->getData();
                $em = $this->doctrine->getManager();
                $em->persist($project);
                $em->flush();

                return true;
            }
        }

        return $form;
    }
}