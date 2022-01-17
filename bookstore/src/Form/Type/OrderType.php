<?php

namespace App\Form\Type;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Order;
use App\Validator\InternationalPhone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('phone', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new InternationalPhone(),
                    new Length([
                        'max' => 50,
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new Length([
                        'max' => 255,
                    ])
                ]
            ])
            ->add('status', IntegerType::class, [
                'constraints' => [
                    new NotNull(),
                    new Choice([
                        'choices' => [0, 1, 2],
                        'message' => 'Status is invalid. (0-pending, 1-processed, 2-delivered)'
                    ])
                ]
            ])
            ->add('books', EntityType::class, [
                'class' => Book::class,
                'multiple' => true,
                'constraints' => [
                    new NotNull(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }

}