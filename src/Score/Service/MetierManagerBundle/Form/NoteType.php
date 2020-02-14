<?php

namespace App\Score\Service\MetierManagerBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note', TextType::class, array(
                'label'    => "Note",
                'required' => true,
                'attr'     => [
                    'placeholder' => "Note"
                ]
            ))
            ->add('etudiant', EntityType::class, array(
                'label' => "Etudiant",
                'class' => "App\Score\Service\MetierManagerBundle\Entity\Etudiant",
                'query_builder' => function(EntityRepository $_er) {
                    return $_er
                        ->createQueryBuilder('et')
                        ->orderBy('et.nom', 'ASC');
                },
                'choice_label' => "nom",
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'placeholder' => "Etudiant"
            ))
            ->add('matiere', EntityType::class, array(
                'label' => "Etudiant",
                'class' => "App\Score\Service\MetierManagerBundle\Entity\Matiere",
                'query_builder' => function(EntityRepository $_er) {
                    return $_er
                        ->createQueryBuilder('mt')
                        ->orderBy('mt.libelle', 'ASC');
                },
                'choice_label' => "libelle",
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'placeholder' => "Matière"
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Score\Service\MetierManagerBundle\Entity\Note'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'service_metiermanagerbundle_note';
    }
}