<?php

namespace App\Form;

use App\Entity\Traits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('rarete')
            ->add('type_de_trait'); 
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                
                $form->add('rouge', CheckboxType::class, [
                    'label' => 'Rouge',
                    'required' => false,
                    'attr' => ['class' => 'custom-checkbox'],
                ]);
    
                $form->add('jaune', CheckboxType::class, [
                    'label' => 'Jaune',
                    'required' => false,
                    'attr' => ['class' => 'custom-checkbox'],
                ]);
    
                // Ajoutez d'autres options de couleur au besoin
            });
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Traits::class,
        ]);
    }
}
