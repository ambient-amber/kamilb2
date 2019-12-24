<?php

namespace App\Form;

use App\Entity\ContentBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('textId')
            ->add('description')
            ->add(
                'content',
                TextareaType::class,
                [
                    'attr' => ['class' => 'js_tinymce_textarea']
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContentBlock::class,
        ]);
    }
}
