<?php

namespace App\Form;

use App\Entity\Projets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProjectsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Vous devez mettre votre nom']),
                ],
            ])

            ->add('Description', TextType::class, [
                'label' => 'Description',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Vous devez mettre votre description']),
                ],
            ])
            ->add('Category', ChoiceType::class, [
                'choices'=>[
                    'Image' => 'Image',
                    'Music' => 'Music',
                    'Gif' => 'Gif'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Vous devez choisir une catégorie']),
                ],
            ])
            ->add('WalletAddress', TextType::class, [
                'label' => 'Adresse du Portefeuille',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Vous devez mettre votre adresse de portefeuille']),
                ],
            ])

            ->add(' traits', CollectionType::class, [
                'entry_type' => TraitsType ::class,
                'allow_add' => true,
                'by_reference' => false,
                'label' => false,
            ]);

            // ->add('image', FileType::class, [
            //     'label' => 'Project Image',
            //     'required' => false,
            //     'mapped' => false, // This field is not mapped to any entity property
            // ]);
    }
       

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projets::class,
        ]);
    }
}
