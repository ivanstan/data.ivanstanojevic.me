<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordRepeatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'user.password.must_match',
            'required' => true,
            'first_options' => [
                'label' => false,
                'attr' => ['placeholder' => 'user.property.password.title'],
            ],
            'second_options' => [
                'label' => false,
                'attr' => ['placeholder' => 'user.property.password.repeat'],
            ],
            'constraints' => [
                new NotBlank([
                    'groups' => 'profile_password',
                    'message' => 'user.password.not_blank',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'user.password.min_length',
                    'max' => 4096,
                ]),
            ],

        ])->addModelTransformer(new CallbackTransformer(
            static function ($passwordAsArray) {
                return $passwordAsArray;
            },
            static function ($passwordAsString) {

                if ($passwordAsString instanceof User) {
                    return $passwordAsString->getPassword();
                }

                return $passwordAsString['password'] ?? $passwordAsString;
            }
        ));
    }
}
