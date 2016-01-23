<?php

namespace Acme\BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Acme\BugBundle\Form\DataTransformer\StringToArrayTransformer;

use Acme\BugBundle\Entity\User;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('full_name','text')
            ->add('username','text')
            ->add($this->getRoles($builder))
            ->add('save', 'submit')
        ;
    }

    public function getName()
    {
        return 'form_admin';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Acme\BugBundle\Entity\User',
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\BugBundle\Entity\User',
        ));
    }

    protected function getRoles(FormBuilderInterface $builder)
    {
        $transformer = new StringToArrayTransformer();
        $attrRole = array_merge(
            array(
                'choices' => array(
                    'ROLE_ADMIN',
                    'ROLE_MANAGER',
                    'ROLE_OPERATOR'
                ),
                'label' => 'Roles',
                'expanded' => false,
                'multiple' => false,
                'mapped' => true
            )
        );
        return $builder->create('roles', 'choice', $attrRole)->addModelTransformer($transformer);
    }
}