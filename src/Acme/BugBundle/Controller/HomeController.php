<?php

namespace Acme\BugBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

use Acme\BugBundle\Security\PostVoter;
use Acme\BugBundle\Entity\User;

class HomeController extends Controller
{
    /**
     * @Route("/", name="main")
     */
    public function homeAction()
    {
        $securityContext = $this->container->get('security.authorization_checker');
            if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                return $this->render('AcmeBugBundle:Registration:confirmed.html.twig');


        } else {
                return $this->redirectToRoute('fos_user_security_login');
            }

    }

}