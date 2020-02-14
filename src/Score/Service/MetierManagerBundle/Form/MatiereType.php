<?php

namespace App\Score\Service\MetierManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EtudiantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label'    => "Nom",
                'required' => true,
                'attr'     => ['placeholder' => "Nom de l'étudiant"]
            ))
            ->add('email', EmailType::class, array(
                'label'    => "Email",
                'required' => false,
                'attr'     => ['placeholder' => "Email de l'étudiant"]
            ))
            ->add('adresse', TextType::class, array(
                'label'    => "Adresse",
                'required' => false,
                'attr'     => ['placeholder' => "Adresse de l'étudiant"]
            ))
            ->add('sexe', ChoiceType::class, array(
                'label'    => "Sexe",
                'choices'  => [
                    'Femme' => 0,
                    'Homme' => 1,
                ],
                'required' => false,
                'placeholder'  => "Veuillez sélectionner votre sexe",
            ))
            ->add('niveau', TextType::class, array(
                'label'    => "Niveau",
                'required' => false,
                'attr'     => ['placeholder' => "Niveau de l'étudiant"]
            ))
            ->add('annee', DateTimeType::class, array(
                'label'    => "Année scolaire",
                'format'   => 'yyyy',
                'widget'   => 'single_text',
                'required' => false
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Score\Service\MetierManagerBundle\Entity\Etudiant'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'service_metiermanagerbundle_etudiant';
    }
}