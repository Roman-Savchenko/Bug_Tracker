<?php
namespace Acme\BugBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT user FROM AcmeBugBundle:User user ORDER BY user.name ASC')
            ->getResult();
    }
}
