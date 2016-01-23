<?php

namespace Acme\BugBundle\Form\Type;

use Acme\BugBundle\Form\DataTransformer\DateTimeToStringTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Acme\BugBundle\Entity\Comment;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', 'text')
           ->add($this->getDate($builder))
            ->add('save', 'submit')
        ;
    }

    public function getName()
    {
        return 'form_comment';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Acme\BugBundle\Entity\Comment',
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\BugBundle\Entity\Comment',
        ));
    }

    public function getDate(FormBuilderInterface $builder)
    {
        $transformer = new DateTimeToStringTransformer();

       return  $builder->create('created', 'hidden')->addModelTransformer($transformer);
    }
}