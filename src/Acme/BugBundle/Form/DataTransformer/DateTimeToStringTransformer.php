<?php

namespace Acme\BugBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class DateTimeToStringTransformer implements DataTransformerInterface
{
    /**
     * @param \DateTime|null $datetime
     *
     * @return string
     */
    public function transform($datetime)
    {
        if (null === $datetime) {
            return '';
        }

        return strstr($datetime->format('Y-m-d h:i:s'),'.',true);
    }

    /**
     * @param  string $datetimeString
     *
     * @return \DateTime
     */
    public function reverseTransform($datetimeString)
    {
        $datetime = new \DateTime($datetimeString);

        return $datetime;
    }
}
