<?php

namespace App\Form;

use App\Entity\Compagnie;
use App\Entity\Itineraire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ItineraireFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('code')
            ->add('description', TextareaType::class)
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Aucun' => 'AUCUN',
                    'Actif' => 'ACTIF',
                    "A l'arrêt" => 'EN_ARRET',
                ]
            ])
            ->add('compagnie', EntityType::class, [
                'class'        => Compagnie::class,
                'multiple'     => false,
                'choice_label' => 'nom',
            ])
            ->add('save', SubmitType::class, array('label' => 'Enregistrer'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Itineraire::class,
        ]);
    }
}
