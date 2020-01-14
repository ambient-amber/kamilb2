<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class ArticleType extends AbstractType
{
    private $locale;

    public function buildForm(FormBuilderInterface $builder, array $data)
    {
        $shop = $data['data'] ?? null;
        $isEdit = $shop && $shop->getId();

        $this->locale = $data['locale'];

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
                'category',
                EntityType::class,
                [
                    'class' => ArticleCategory::class,
                    'choice_label' => function(ArticleCategory $articleCategory) {
                        $translations = $articleCategory->getArticleCategoryTranslations();

                        foreach ($translations as $translation) {
                            if ($this->locale == $translation->getLanguage()->getTextId()) {
                                $returnValue = $translation->getTitle();
                            }
                        }

                        if (!isset($returnValue)) {
                            $returnValue = 'У категории не задан заголовок';
                        }

                        return $returnValue;
                    }
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
            'locale' => 'ru'
        ]);

        $resolver->setAllowedTypes('locale', 'string');
    }
}
