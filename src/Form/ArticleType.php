<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $shop = $options['data'] ?? null;
        $isEdit = $shop && $shop->getId();

        $builder
            ->add('url')
            ->add('pub')
            ->add(
                'plainImage',
                FileType::class,
                [
                    'required' => $isEdit ? false : true,
                    'mapped' => false,
                    'constraints' => [
                        new Image(
                            ['maxSize' => '5M']
                        )
                    ]
                ]
            )
            ->add(
                'articleTranslations',
                CollectionType::class,
                [
                    'entry_type' => ArticleTranslationType::class,
                    'entry_options' => ['label' => false],
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
