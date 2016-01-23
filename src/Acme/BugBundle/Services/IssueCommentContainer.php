<?php

namespace Acme\BugBundle\Services;

use Acme\BugBundle\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Registry;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class IssueCommentContainer
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
    public function __construct(FormFactory $FormFactory, Registry $doctrine, Comment $comment)
    {
        $this->FormFactory = $FormFactory;
        $this->doctrine = $doctrine;
        $this->comment = $comment;
    }

    /**
     * @param $code
     * @param Request $request
     * @param $userId
     * @return bool|Form|FormInterface
     */
    public function IssueComment($code,Request $request,$userId)
    {
        $comment = $this->comment;
        $issueId = $this->doctrine
            ->getRepository('AcmeBugBundle:Issue')
            ->findOneByCode($code);
        $form = $this->FormFactory->create('form_comment', $comment);
        if ($request->getMethod() === 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $comment = $form->getData();
                $comment->setAuthor($userId);
                $comment->setIssue($issueId);
                $em = $this->doctrine->getManager();
                $em->persist($comment);
                $em->flush();

                return true;
            }
        }

        return $form;
    }
}