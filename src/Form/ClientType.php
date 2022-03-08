<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Numcli', TextType::class, [
                'attr' => [
                    'readOnly' => true,
                ]
            ])
            ->add('Nom', TextType::class, [
                'label' => 'Noms: ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field cannot be empty.'
                    ])
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Upload Image(PNG or JPG)',
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
