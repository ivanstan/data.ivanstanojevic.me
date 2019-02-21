<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordChangeType;
use App\Form\PasswordRecoveryType;
use App\Security\RecoveryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorTrait;

class SecurityController extends AbstractController
{
    use TranslatorTrait;

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/settings", name="security_settings")
     */
    public function settings(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PasswordChangeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $entityManager->flush();

            $this->addFlash('success', $this->trans('You have successfully changed your password.'));

            return $this->redirectToRoute('security_settings');
        }

        return $this->render('pages/security/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recovery", name="security_recovery")
     */
    public function recovery(Request $request, RecoveryService $recovery): Response
    {
        $form = $this->createForm(PasswordRecoveryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $data['email']]);

            if ($user) {
                try {
                    $recovery->request($user);
                } catch (\Exception $e) {
                    $this->addFlash('danger', $this->trans('Unable to send message.'));
                }
            }

            $this->addFlash('success', $this->trans('Recovery instructions are sent to email.'));

            return $this->redirectToRoute('security_recovery');
        }

        return $this->render('pages/security/recovery.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/invitation/{token}", name="security_invitation_token")
     * @Route("/recover-password/{token}", name="security_recovery_token")
     */
    public function recoverToken(string $token, RecoveryService $recovery): RedirectResponse
    {
        if ($recovery->recover($token)) {
            $this->addFlash('success', $this->trans('Authenticated successfully. You may now change your password.'));

            return $this->redirectToRoute('security_settings');
        }

        $this->addFlash('danger', $this->trans('Invalid access token. Please try again.'));

        return $this->redirectToRoute('security_recovery');
    }
}
