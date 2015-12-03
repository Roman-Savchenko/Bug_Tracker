<?php

namespace Acme\BugBundle\Entity;

use Acme\BugBundle\Entity\Project;

class Helper
{
    /**
     * @param $entity
     * @return string
     */
    public function getCode($entity)
    {
//        $id = $entity->getId();
//        $code = $id.'/'.$this->getAbbreviation($this->getLabel($entity));

        $label = $this->getLabel($entity);
        $abbreviation = $this->getAbbreviation($label);
        $code = sprintf('%s-%s',$abbreviation,$entity->getId());

        return $code;
    }

    /**
     * @param $entity
     * @return string
     */
    protected function getLabel($entity)
    {
        $label = '';

        if ($entity instanceof Project) {
            $label = $entity->getLabel();
        } elseif ($entity instanceof Issue) {
            $label = $entity->getSummary();
        }

        return $label;
    }

    /**
     * @param $label
     * @return string
     */
    protected function getAbbreviation($label)
    {
        $abbreviation = '';
        $label= explode(' ',ucwords($label));

        foreach($label as $value)
        {
            $abbreviation .= substr($value,0,1);
        }

        return  $abbreviation;
    }
}