<?php

namespace App\Form;

use App\Entity\Compagnie;
use App\Entity\Localite;
use App\Entity\Passage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('coordonnees')
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Enregistrer'))
            ->add('compagnie', EntityType::class, [
                'class'        => Compagnie::class,
                'multiple'     => false,
                'choice_label' => 'nom',
            ])
            ->add('localite', EntityType::class, [
                'class'        => Localite::class,
                'multiple'     => false,
                'choice_label' => 'nom',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Passage::class,
        ]);
    }
}
