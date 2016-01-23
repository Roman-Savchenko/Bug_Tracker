<?php

namespace Acme\BugBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use Acme\BugBundle\Entity\Activity;
use Acme\BugBundle\Entity\Issue;

class IssueEventListener
{
    /**
     * @var TokenStorage
     */
    protected $securityTokenStorage;

    /**
     * @param TokenStorage $securityTokenStorage
     */
    public function __construct(TokenStorage $securityTokenStorage)
    {
        $this->securityTokenStorage = $securityTokenStorage;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $issue = $eventArgs->getEntity();

        if ($issue instanceof Issue) {
            if (!$issue->getCollaborators()->contains($issue->getReporter())) {
                $issue->getCollaborators()->add($issue->getReporter());
            }
            if (!$issue->getCollaborators()->contains($issue->getAssignee())) {
                $issue->getCollaborators()->add($issue->getAssignee());
            }
        }
    }

    /**
     * @param PreUpdateEventArgs $eventArgs
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $issue = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();

        if ($issue instanceof Issue) {

            $user = $this->securityTokenStorage->getToken()->getUser();
            if ($eventArgs->hasChangedField('status')) {
                $eventEntity = new Activity();
                $eventEntity->setChangeStatus(new \DateTime());
                $entityManager->persist($eventEntity);
                $entityManager->flush();

                if (!$issue->getCollaborators()->contains($user)) {
                    $issue->getCollaborators()->add($user);
                }
            }
            if ($this->hasChangedReporter($eventArgs)) {
                $issue->getCollaborators()->add($issue->getReporter());
            }
            if ($this->hasChangedAssignee($eventArgs)) {
                $issue->getCollaborators()->add($issue->getAssignee());
            }
        }
    }

    /**
     * @param PreUpdateEventArgs $eventArgs
     *
     * @return bool
     */
    private function hasChangedAssignee(PreUpdateEventArgs $eventArgs)
    {
        $issue = $eventArgs->getEntity();

        return $eventArgs->hasChangedField('assignee') && !$issue->getCollaborators()->contains($issue->getAssignee());
    }
    /**
     * @param PreUpdateEventArgs $eventArgs
     *
     * @return bool
     */
    private function hasChangedReporter(PreUpdateEventArgs $eventArgs)
    {
        $issue = $eventArgs->getEntity();

        return $eventArgs->hasChangedField('reporter') && !$issue->getCollaborators()->contains($issue->getReporter());
    }

}