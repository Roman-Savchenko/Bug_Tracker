<?php

namespace Acme\BugBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Acme\BugBundle\Entity\User;
use Acme\BugBundle\Form\Type;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registrationAction(Request $request)
    {
        $user = new User();
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/profile/edit", name="profile_edit")
     */
    public function profile_editAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->container->get('user.edit.profile')->UserEditProfile($request,$user);

        if ($form instanceof FormInterface) {
            return $this->render('AcmeBugBundle:User:edit_profile.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        return $this->redirectToRoute('new_profile');
    }

    /**
     * @Route("/profile", name="new_profile")
     */
    public function profileAction(Request $request)
    {
        $user = $this->getUser();
        return $this->render("AcmeBugBundle:User:profile.html.twig", array(
            'user' => $user,
        ));
    }


}