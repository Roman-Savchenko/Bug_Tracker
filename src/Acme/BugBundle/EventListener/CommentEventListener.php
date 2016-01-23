<?php

namespace Acme\BugBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Acme\BugBundle\Entity\Activity;
use Acme\BugBundle\Entity\Comment;

class CommentEventListener
{
    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $comment = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();

        if ($comment instanceof Comment) {
            $issue = $comment->getIssue();
            $eventEntity = new Comment();
            $eventEntity->setCreated(new \DateTime());
            $entityManager->persist($eventEntity);
            $entityManager->flush();
            if (!$issue->getCollaborators()->contains($comment->getAuthor())) {
                $issue->addCollaborator($comment->getAuthor());
                $entityManager->persist($issue);
                $entityManager->flush();
            }
        }
    }
}