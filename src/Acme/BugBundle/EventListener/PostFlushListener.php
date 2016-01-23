<?php

namespace Acme\BugBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;

use Acme\BugBundle\Entity\GenerateCodeInterface;
use Acme\BugBundle\Entity\Helper;


class PostFlushListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof GenerateCodeInterface) {
            $entityManager = $args->getEntityManager();
            $this->setCode($entity,$entityManager);
        }

        return;
    }

    /**
     * @param GenerateCodeInterface $entity
     * @param EntityManager $entityManager
     */
    protected function setCode(GenerateCodeInterface $entity, EntityManager $entityManager)
    {
        $helper = new Helper();
        $code = $helper->getcode($entity);
        $entity->setCode($code);
        $entityManager->persist($entity);
        $entityManager->flush();
    }


}
