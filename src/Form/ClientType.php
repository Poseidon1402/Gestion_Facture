<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Numcli', TextType::class, [
                'label' => 'Numéro du Client',
                'attr' => [
                    'readOnly' => true,
                ]
            ])
            ->add('Nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field cannot be empty.'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => "Le nom doit contenir au moins { limit } caractères.",
                        'max' => 150,
                        'maxMessage' => "Le nom ne peut contenir que { limit } caractères"
                    ])
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Upload Image (PNG or JPG)',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'imagine_pattern' => 'squared_thumbnail_small'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'required' => false
        ]);
    }
}
