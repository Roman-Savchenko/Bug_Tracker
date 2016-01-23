<?php

namespace Acme\BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Acme\BugBundle\Form\DataTransformer\DateTimeToStringTransformer;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = array(
            'bug'=> 'Bug',
            'task'=>'Task',
            'story'=>'Story',
            'subtask'=>'Subtask'
        );
        $builder
            ->add('summary','textarea')
            ->add('description','textarea')
            ->add('type','choice',array('choices'=>$type))
            ->add('priority','text')
            ->add('status','text')
            ->add('reporter','entity',array(
                'class'=>'AcmeBugBundle:User',
                'choice_label'=>'full_name'
            ))
            ->add('assignee','entity',array(
                'class'=>'AcmeBugBundle:User',
                'choice_label'=>'full_name'))
            ->add('project','entity',array(
                'class'=>'AcmeBugBundle:Project',
                'choice_label'=>'label'
            ))
            ->add($this->getDate($builder))
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

    public function getDate(FormBuilderInterface $builder)
    {
        $transformer = new DateTimeToStringTransformer();

        return $builder->create('created', 'hidden')->addModelTransformer($transformer);
    }
}