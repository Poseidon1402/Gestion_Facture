<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numPro', TextType::class,['attr'=>['readonly'=>true]])
            ->add('design',TextType::class,['constraints'=>new NotBlank(['message' => 'This field cannot be empty.'])])
            ->add('pu',NumberType::class,['constraints'=>new NotBlank(['message' => 'This field cannot be empty.'])])
            ->add('stock',IntegerType::class,['constraints'=>new NotBlank(['message' => 'This field cannot be empty.'])])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'required'=>false        ]);
    }
}
