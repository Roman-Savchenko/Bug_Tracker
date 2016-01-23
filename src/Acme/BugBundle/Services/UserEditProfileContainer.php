<?php

namespace Acme\BugBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class UserEditProfileContainer
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
    public function UserEditProfile(Request $request, $user)
    {
        $form = $this->FormFactory->create('profile_edit', $user);
        if ($request->getMethod() === 'POST') {
            $form->handleRequest();
            if ($form->isValid()) {
                $user = $form->getData();
                $em = $this->doctrine->getEntityManager();
                $em->persist($user);
                $em->flush();

                return true;
            }
        }

        return $form;
    }

}