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
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();
            $isNew = !$user || null === $user->getId();

            $form->add('email', EmailType::class, [
                'constraints' => [new Email()],
                'disabled' => !$isNew,
            ]);

            if ($isNew) {
                $form->add('password', PasswordRepeatType::class, ['label' => false]);
            }

            $form->add('roles', ChoiceType::class, [
                'choices' => User::ROLES,
                'expanded' => true,
                'multiple' => true,
                'choice_attr' => function ($key) {
                    return $key === User::ROLE_USER ? ['disabled' => 'disabled'] : [];
                },
            ]);
            $form->add('active', CheckboxType::class, [
                'required' => false,
            ]);
            $form->add('verified', CheckboxType::class, [
                'required' => false,
            ]);

            if ($isNew) {
                $form->add('invite', CheckboxType::class, [
                    'required' => false,
                    'mapped' => false,
                    'label' => 'user.form.invite.title',
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
