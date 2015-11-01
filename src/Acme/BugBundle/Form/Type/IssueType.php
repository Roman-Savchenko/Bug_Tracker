<?php

namespace Acme\BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary','textarea')
            ->add('code','textarea')
            ->add('description','textarea')
            ->add('type','choice')
            ->add('priority','text')
            ->add('status','choice')
            ->add('resolution','text')
            ->add('reporter','text')
            ->add('assignee','text')
            ->add('collaborator','choice')
            ->add('created','date')
            ->add('save', 'submit')
        ;
    }

    public function getName()
    {
        return 'form_issue_registration';
    }
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Acme\BugBundle\Entity\Issue',
        );
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\BugBundle\Entity\Issue',
        ));
    }
}