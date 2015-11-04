<?php
namespace Acme\BugBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT project FROM AcmeBugBundle:Project project ORDER BY project.name ASC')
            ->getResult();
    }
}
