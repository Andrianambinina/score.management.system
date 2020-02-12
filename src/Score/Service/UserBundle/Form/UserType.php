<?php

namespace App\Score\Service\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType
 * @package App\Score\Service\UserBundle\Form
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, array(
                'label'    => "Nom",
                'required' => true
            ))

            ->add('firstname', TextType::class, array(
                'label'    => "Prénom",
                'required' => true
            ))

            ->add('address', TextType::class, array(
                'label'    => "Adresse",
                'required' => false
            ))

            ->add('phone', TextType::class, array(
                'label'    => "Téléphone",
                'required' => false
            ))

            ->add('email', EmailType::class, array(
                'label'    => "Adresse email",
                'attr'     => array('pattern' => "[^@]+@[^@]+\.[a-zA-Z]{2,}"),
                'required' => true
            ))

            ->add('plainPassword',RepeatedType::class, array(
                'type'            => PasswordType::class,
                'options'         => array('translation_domain' => 'FOSUserBundle'),
                'first_options'   => array(
                    'label' => 'form.password',
                    'attr'  => array('minleght' => 6)
                ),
                'second_options'  => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Score\Service\UserBundle\Entity\User',
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'prs_userbundle_user';
    }
}