<?php

namespace App\Form;

use App\Entity\ArticleTranslation;
use App\Entity\Language;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
            'language',
            EntityType::class,
                [
                    'class' => Language::class,
                    'choice_label' => 'text_id',
                ]
            )
            ->add('title')
            ->add(
                'previewContent',
                TextareaType::class,
                [
                    'attr' => ['class' => 'js_tinymce_textarea js_tinymce_simple']
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'attr' => ['class' => 'js_tinymce_textarea']
                ]
            )
            ->add(
                'delete',
                ButtonType::class,
                [
                    'attr' => ['class' => 'btn-danger btn-sm js_delete_translation']
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleTranslation::class,
        ]);
    }
}
