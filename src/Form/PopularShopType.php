<?php

namespace App\Form;

use App\Entity\PopularShop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class PopularShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('url')
            ->add('plainImage', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Image(
                        ['maxSize' => '5M']
                    )
                ]
            ])
            ->add('pub')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PopularShop::class,
        ]);
    }
}
