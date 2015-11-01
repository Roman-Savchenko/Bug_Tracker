<?php
namespace Acme\BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('full_name','text')
            ->add('username','text')
            ->add('email','text')
            ->add('avatar','file')
            ->add('password', 'password')
            ->add('save', 'submit')
        ;
    }

    public function getName()
    {
        return 'form_user_registration';
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
}