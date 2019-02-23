<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordChangeType;
use App\Form\PasswordRecoveryType;
use App\Form\RegisterType;
use App\Security\SecurityMailerService;
use App\Service\Traits\TranslatorAwareTrait;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController implements LoggerAwareInterface
{
    use TranslatorAwareTrait;
    use LoggerAwareTrait;

    private $securityMailer;

    private $encoder;

    public function __construct(SecurityMailerService $securityMailer, UserPasswordEncoderInterface $encoder)
    {
        $this->securityMailer = $securityMailer;
        $this->encoder = $encoder;
    }

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
    public function settings(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PasswordChangeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('You have successfully changed your password.'));

            return $this->redirectToRoute('security_settings');
        }

        return $this->render('pages/security/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register", name="security_register")
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
            $user->setActive(true);
            $user->setVerified(false);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->logger->info(sprintf('New user %s has registered', $user->getEmail()));

            $this->securityMailer->requestVerification($user);
            $this->addFlash('success', $this->translator->trans('Account verification email has been sent.'));
            $this->redirectToRoute('security_login');
        }

        return $this->render('pages/security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recovery", name="security_recovery")
     */
    public function recovery(Request $request): Response
    {
        $form = $this->createForm(PasswordRecoveryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $data['email']]);

            if ($user) {
                $this->securityMailer->requestRecovery($user);
            }

            $this->logger->info(sprintf('User %s has requested password recovery', $user->getEmail()));

            $this->addFlash('success', $this->translator->trans('Recovery instructions are sent to email.'));

            return $this->redirectToRoute('security_recovery');
        }

        return $this->render('pages/security/recovery.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify", name="user_verify", methods={"POST"})
     */
    public function verify(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('verify'.$user->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('app_index');
        }

        if (!$user->isVerified()) {
            $this->logger->info(sprintf('User %s requested verification', $user->getEmail()));

            $this->addFlash('info',
                $this->translator->trans('Hey <strong>%email%</strong> email account owner. You will shortly receive verification email.',
                    [
                        '%email%' => $user->getEmail(),
                    ]));

            $this->securityMailer->requestVerification($user);
        }

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/verify/{token}", name="security_verification_token")
     */
    public function verifyToken(string $token): RedirectResponse
    {
        $user = $this->securityMailer->verify($token);

        if ($user === null) {
            $this->addFlash('danger', $this->translator->trans('Invalid access token. Please try again.'));

            return $this->redirectToRoute('security_recovery');
        }

        $this->logger->info(sprintf('User %s has verified', $user->getEmail()));

        $this->addFlash('success', $this->translator->trans('Account verified successfully.'));

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/recover-password/{token}", name="security_recovery_token")
     */
    public function recoverToken(string $token): RedirectResponse
    {
        $user = $this->securityMailer->recover($token);

        if ($user === null) {
            $this->addFlash('danger', $this->translator->trans('Invalid access token. Please try again.'));

            return $this->redirectToRoute('security_recovery');
        }

        $this->logger->info(sprintf('User %s has used login token', $user->getEmail()));

        $this->addFlash('success', $this->translator->trans('Authenticated successfully. You may now change your password.'));

        return $this->redirectToRoute('security_settings');
    }

    /**
     * @Route("/invitation/{token}", name="security_invitation_token")
     */
    public function invitation(string $token)
    {
        $user = $this->securityMailer->recover($token);

        if ($user === null) {
            $this->addFlash('danger', $this->translator->trans('Invalid access token. Please try again.'));

            return $this->redirectToRoute('security_recovery');
        }

        $this->logger->info(sprintf('User %s has verified account', $user->getEmail()));

        $this->addFlash('success', $this->translator->trans('You have successfully verified your account.'));

        return $this->redirectToRoute('app_index');
    }
}
