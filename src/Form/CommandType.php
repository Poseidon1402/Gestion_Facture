<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clients', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez un client',
                'query_builder' => fn(ClientRepository $rep) => $rep->createQueryBuilder('c')
                ->orderBy('c.nom', 'ASC')
            ])
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'design',
                'placeholder' => 'Choisissez un produit',
                'query_builder' => fn(ProduitRepository $rep) => $rep->createQueryBuilder('p')
                ->orderBy('p.design', 'ASC')
            ])
            ->add('qte', NumberType::class)
            ->add('date_commande', TypeDateType::class, [
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'required' => false
        ]);
    }
}
