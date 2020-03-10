<?php

namespace App\Score\Service\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                'required' => true,
                'attr'     => [
                    'placeholder' => "Nom"
                ]
            ))
            ->add('firstname', TextType::class, array(
                'label'    => "Prénom",
                'required' => true,
                'attr'     => [
                    'placeholder' => "Prénom"
                ]
            ))
            ->add('address', TextType::class, array(
                'label'    => "Adresse",
                'required' => false,
                'attr'     => [
                    'placeholder' => "Adresse"
                ]
            ))
            ->add('phone', TextType::class, array(
                'label'    => "Téléphone",
                'required' => false,
                'attr'     => [
                    'placeholder' => "Téléphone"
                ]
            ))
            ->add('email', EmailType::class, array(
                'label'    => "Adresse email",
                'required' => true,
                'attr'     => [
                    'pattern' => "[^@]+@[^@]+\.[a-zA-Z]{2,}",
                    'placeholder' => "Email"
                ]
            ))
            ->add('photo', FileType::class, array(
                'label'    => "Photo de profil",
                'required' => false
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type'            => PasswordType::class,
                'options'         => ['translation_domain' => 'FOSUserBundle'],
                'first_options'   => [
                    'label' => 'form.password',
                    'attr'  => array('minleght' => 6)
                ],
                'second_options'  => ['label' => 'form.password_confirmation'],
                'invalid_message' => 'fos_user.password.mismatch'
            ));
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