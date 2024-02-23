<?php

namespace App\Form;

use App\Entity\Traits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('rarete')
            ->add('type_de_trait')
            ->add('Couleur', Choice::class, [
                'choices' => [
                    'Rouge' => 'rouge',
                    'Jaune' => 'jaune',
                    // Ajoutez d'autres options de couleur au besoin
                ],
                'multiple' => true, // Permettre des choix multiples
                'expanded' => true, // Afficher les choix comme des cases à cocher (si désiré)
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Traits::class,
        ]);
    }
}
