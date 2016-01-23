<?php

namespace Acme\BugBundle\Form\Type;

use Acme\BugBundle\Form\DataTransformer\DateTimeToStringTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Acme\BugBundle\Entity\Comment;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($this->getDate($builder))
        ;
    }

    public function getName()
    {
        return 'form_activity';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Acme\BugBundle\Entity\Activity',
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\BugBundle\Entity\Activity',
        ));
    }

    public function getDate(FormBuilderInterface $builder)
    {
        $transformer = new DateTimeToStringTransformer();

        return  $builder->create('change_status', 'hidden')->addModelTransformer($transformer);
    }
}