<?php

namespace App\Form;

use App\Entity\Language;
use App\Entity\MetaData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url')
            ->add(
                'language',
                EntityType::class,
                [
                    'class' => Language::class,
                    'choice_label' => 'text_id',
                ]
            )
            ->add('pub')
            ->add('isRegexp')
            ->add('isTemplate')
            ->add('title')
            ->add('description')
            ->add('keyWords')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MetaData::class,
        ]);
    }
}
