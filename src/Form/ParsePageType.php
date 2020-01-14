<?php

namespace App\Form;

use App\Entity\ArticleCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParsePageType extends AbstractType
{
    private $locale;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->locale = $options['locale'];

        $builder
            ->add(
                'pageUrl',
                TextType::class,
                [
                    'mapped' => false,
                    'required' => true,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'locale' => 'ru'
        ]);

        $resolver->setAllowedTypes('locale', 'string');
    }
}
