<?php

namespace App\Form;

use App\Entity\Banner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class BannerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $shop = $data['data'] ?? null;
        $isEdit = $shop && $shop->getId();

        $builder
            ->add('link')
            ->add('pub')
            ->add('onIndex')
            ->add('onArticle')
            ->add('onArticleCategory')
            ->add(
                'plainImage',
                FileType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'constraints' => [
                        new Image(
                            ['maxSize' => '5M']
                        )
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Banner::class,
        ]);
    }
}
