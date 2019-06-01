<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordRepeatType;
use App\Form\UserType;
use App\Service\Traits\TranslatorAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileController extends AbstractController
{
    use TranslatorAwareTrait;

    /**
     * @Route("/user/profile", name="user_profile_edit")
     * @//IsGranted("ROLE_USER") todo: fix
     */
    public function profile(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $preference = $user->getPreference();
        if (!$preference->getId()) {
            $preference->setTimezone($this->getParameter('default_timezone'));
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!$preference->getId()) {
                $user->setPreference($preference);
                $em->persist($preference);
            }

            $em->flush();

            return $this->redirectToRoute('user_profile_edit');
        }

        return $this->render(
            'pages/user/profile.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/user/security", name="user_profile_security")
     * @//IsGranted("ROLE_USER") todo: fix
     */
    public function security(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $builder = $this->createFormBuilder()
            ->add(
                'currentPassword',
                PasswordType::class,
                [
                    'constraints' => new UserPassword(),
                    'label' => false,
                    'attr' => ['placeholder' => 'security.current_password'],
                ]
            )
            ->add('password', PasswordRepeatType::class, ['label' => false]);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user->setPassword($data['password']);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', $this->translator->trans('user.messages.password.change'));

            return $this->redirectToRoute('user_profile_security');
        }

        return $this->render(
            'pages/user/security.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

}
