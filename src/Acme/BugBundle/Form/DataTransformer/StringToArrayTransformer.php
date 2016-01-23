<?php

namespace Acme\BugBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

use Acme\BugBundle\Entity\User;

class StringToArrayTransformer implements DataTransformerInterface
{
    /**
     * Transforms an array to a string.
     * POSSIBLE LOSS OF DATA
     *
     * @param mixed $array
     *
     * @return string
     */
    public function transform($array)
    {
        return $array[0];
    }
    /**
     * Transforms a string to an array.
     *
     * @param  string $string
     *
     * @return array
     */
    public function reverseTransform($string)
    {
        return array($string);
    }
}