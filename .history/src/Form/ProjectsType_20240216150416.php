<?php

namespace App\Form;

use App\Entity\Projets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('brochure', FileType::class, [
                'label' => 'Brochure (PDF file)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ]);
    }
       

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projets::class,
        ]);
    }
}
