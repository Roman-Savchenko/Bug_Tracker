<?php
namespace Acme\BugBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{

    public function timezone()
    {
        $time_zone = array(
            '0'=>'Moscow/Europe',
            '1'=>'Tokyo/Asia'
        );
        return $time_zone;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text')
            ->add('full_name','text')
            ->add('username','text')
            ->add('email','text')
            ->add('avatar','file')
            ->add('timezone','choice', array ('choices'=> $this->timezone()))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('save', 'submit')
        ;
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

    public function getName()
    {
        return 'app_user_registration';
    }
}