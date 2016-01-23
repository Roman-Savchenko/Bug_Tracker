<?php
namespace Acme\BugBundle\Security;

use Acme\BugBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;

class PostProjectVoter extends AbstractVoter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function getSupportedAttributes()
    {
        return array(self::VIEW, self::EDIT);
    }

    protected function getSupportedClasses()
    {
        return array('Acme\BugBundle\Entity\Project');
    }

    protected function isGranted($attribute, $project, $user = null)
    {
        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return false;
        }

        // double-check that the User object is the expected entity (this
        // only happens when you did not configure the security system properly)
        if (!$user instanceof User) {
            throw new \LogicException('The user is somehow not our User class!');
        }

        switch($attribute) {
            case self::VIEW:
                // the data object could have for example a method isPrivate()
                // which checks the Boolean attribute $private
                if ($user->getId() === $project->getMembers()->getowner()->getId()) {
                    return true;
                }

                break;
        }

        return false;
    }
}