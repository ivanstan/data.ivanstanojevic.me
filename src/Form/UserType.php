<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class, [
            'constraints' => [new Email()],
            'disabled' => true,
        ]);

        if (!$options['edit']) {
            $builder->add('password', PasswordChangeType::class, ['label' => false]);
        }

        $builder->add('roles', ChoiceType::class, [
            'choices' => User::ROLES,
            'expanded' => true,
            'multiple' => true,
            'choice_attr' => function ($key) {
                return $key === User::ROLE_USER ? ['disabled' => 'disabled'] : [];
            },
        ]);
        $builder->add('active', CheckboxType::class, [
            'required' => false,
        ]);
        $builder->add('verified', CheckboxType::class, [
            'required' => false,
        ]);

        if (!$options['edit']) {
            $builder->add('invite', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'user.form.invite.title'
            ]);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();

            if (!$user || null === $user->getId()) {
                $form->add('email', EmailType::class, [
                    'constraints' => [new Email()],
                    'disabled' => false,
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'edit' => false,
        ]);
    }
}
