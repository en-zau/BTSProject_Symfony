<?php

namespace App\Form;

use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddEnchereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('date_debut', DateTimeType::class, [
                'label' => "Date debut",
                'empty_data' => '',
                'required' => true,
                'data' => new \DateTime("now"),
            ])
            ->add('date_fin', DateTimeType::class, [
                'label' => "Date fin",
                'empty_data' => '',
                'required' => true,
                'data' => new \DateTime("now"),
            ])
            ->add('id_panier_globale')


        ;
    }

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}