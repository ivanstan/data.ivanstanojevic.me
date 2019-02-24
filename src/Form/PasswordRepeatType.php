<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordRepeatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'required' => true,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
            'constraints' => [
                new NotBlank([
                    'groups' => 'profile_password',
                    'message' => 'Password should not be blank',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least 6 characters',
                    'max' => 4096,
                ]),
            ],

        ])->addModelTransformer(new CallbackTransformer(
            function ($passwordAsArray) {
                return $passwordAsArray;
            },
            function ($passwordAsString) {

                if ($passwordAsString instanceof User) {
                    return $passwordAsString->getPassword();
                }

                return $passwordAsString['password'];
            }
        ));
    }
}
