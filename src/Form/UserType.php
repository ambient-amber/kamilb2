<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['data'] ?? null;
        $isEdit = $user && $user->getId();

        $passwordFieldOptions = [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'password-field']],
            'first_options'  => ['label' => 'Пароль'],
            'second_options' => ['label' => 'Повторите пароль'],
            'required' => false
        ];

        if (!$isEdit) {
            $passwordFieldOptions['required'] = true;

            $passwordFieldOptions['constraints'] = new Length([
                'min' => 6,
                'max' => 15
            ]);
        }

        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_ADMIN_CONTENT' => 'ROLE_ADMIN_CONTENT',
                ],
                'expanded'  => true,
                'multiple'  => true,
                'empty_data' => []
            ])
            ->add(
                'plainPassword',
                RepeatedType::class,
                $passwordFieldOptions
            );
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
