<?php

namespace App\Form;

use App\Entity\Arret;
use App\Entity\Passage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArretType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('temps')
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Aucun' => 'AUCUN',
                    'Actif' => 'ACTIF',
                    "A l'arrêt" => 'EN_ARRET',
                ]
            ])
            ->add('passage', EntityType::class, [
                'class'        => Passage::class,
                'multiple'     => false,
                'choice_label' => 'nom',
            ])
            ->add('save', SubmitType::class, array('label' => 'Enregistrer'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Arret::class,
        ]);
    }
}
