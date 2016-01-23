<?php

namespace Acme\BugBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Acme\BugBundle\Form\Type;
use Acme\BugBundle\Repository;
use Acme\BugBundle\Entity\User;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */

    public function adminAction(Request $request)
    {
        $users = $this->getDoctrine()
            ->getRepository('AcmeBugBundle:User')
            ->findAll();
        return $this->render('AcmeBugBundle:Admin:admin.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/admin/{id}/edit_profile", name="edit_profile")
     */
    public function editprofileAction($id,Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('AcmeBugBundle:User')
            ->find($id);

       $form = $this->container->get('admin.edit.profile')->Admineditprofile($request,$user);

        if ( $form instanceof FormInterface ) {
            return $this->render('AcmeBugBundle:Admin:admin_change_profile.html.twig', array(
                'form' => $form->createView(),
                'user' => $user
            ));
        }

        return $this->redirectToRoute('main');
    }


    /**
     * @Route("/admin/{id}", name="admin_user_view")
     */
    public function viewuserAction($id)
    {

        $user = $this->getDoctrine()
            ->getRepository('AcmeBugBundle:User')
            ->find($id);
        return  $this->render('AcmeBugBundle:Admin:viewuser.html.twig', array(
            'user'=>$user
        ));
    }


}