<?php

namespace Acme\BugBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\BugBundle\Entity\User;
use Acme\BugBundle\Form\Type;
use Acme\BugBundle\Entity\Role;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registrationAction(Request $request)
    {

        $user = new User();
////        $user->addRole(Role::manager);
        $form = $this->createForm('form_user_registration', $user);
        return $this->render('AcmeBugBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/main_activity", name="main_activity")
     */
    public function confirmAction(Request $request)
    {

        return $this->render('AcmeBugBundle:Registration:confirmed.html.twig');
    }

    /**
     * @Route("/profile", name="new_profile")
     */
    public function profileAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('app_user_registration', $user);
        return $this->render('AcmeBugBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}