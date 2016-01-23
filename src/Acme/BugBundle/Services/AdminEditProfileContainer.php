<?php

namespace Acme\BugBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class AdminEditProfileContainer
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
     * @param $user
     * @return bool|Form|FormInterface
     */
    public function Admineditprofile(Request $request,$user)
    {
        $form = $this->FormFactory->create('form_admin', $user);
        if ($request->getMethod() === 'POST') {
            $form->get('roles')->submit($this->roles());

            if ($form->isValid()) {
                $user = $form->getData();
                $em = $this->doctrine->getManager();
                $em->persist($user);
                $em->flush();

                return true;
            }
        }

        return $form;
    }

    /**
     * @return array
     */
    protected function roles()
    {
        $roles = array(
            'ROLE_ADMIN' => 'ROLE_ADMIN',
            'ROLE_MANAGER'=>'ROLE_MANAGER',
            'ROLE_OPERATOR'=>'ROLE_OPERATOR',
        );
        return $roles;
    }

}