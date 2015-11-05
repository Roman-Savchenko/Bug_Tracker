<?php

namespace Acme\BugBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\BugBundle\Entity\User;
use Acme\BugBundle\Form\Type;


class UserController extends Controller
{


    /**
     * @Route("/main", name="form_user")
     */
    public function registrationAction(Request $request)
    {

        $user = new User();
        $form = $this->createForm('form_user_registration', $user);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $user = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('main_activity');
            }
        }

        return $this->render('AcmeBugBundle:Registration:registration.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("main_activity", name="main_activity")
     */
    public function confirmAction(Request $request)
    {
        return $this->render('AcmeBugBundle:Registration:confirm.html.twig');
    }

    /**
     * @Route("/profile", name="new_profile")
     */
    public function profileAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('form_user_registration', $user);
        return $this->render('AcmeBugBundle:Registration:registration.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}