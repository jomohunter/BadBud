<?php

namespace App\Form;

use App\Entity\NFT;
use PHPUnit\Framework\Constraint\LessThan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan as ConstraintsLessThan;

class NFTType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price', NumberType::class, [
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 10,
                        'message' => 'The price must be more than 10.',
                    ]),
                    new ConstraintsLessThan([
                        'value' => 666,
                        'message' => 'The price must be less than 666.',
                    ]),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Sell' => 'sell',
                    'Private' => 'private',
                    'Auction' => 'auction',
                ],
                'constraints' => [
                    new Choice([
                        'choices' => ['sell', 'private', 'auction'],
                        'message' => 'Please select a valid status.',
                    ]),
                ],
            ])
            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NFT::class,
        ]);
    }
}
