<?php

namespace Acme\BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
 {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label','text')
            ->add('summary','textarea')
            ->add('members', null,['multiple'=> true])
            ->add('save', 'submit')
        ;
    }

    public function getName()
    {
        return 'form_project_registration';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Acme\BugBundle\Entity\Project',
            'user'=>array()
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\BugBundle\Entity\Project',
            'user'=>array()
        ));
    }
 }