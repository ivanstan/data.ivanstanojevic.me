<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserAdminType;
use App\Security\SecurityMailerService;
use App\Service\Traits\TranslatorAwareTrait;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class UserController extends AbstractController implements LoggerAwareInterface
{
    use TranslatorAwareTrait;
    use LoggerAwareTrait;

    /**
     * @Route("/users", name="user_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $builder = $this->getDoctrine()->getRepository(User::class)->findAll(
            $request->get('search')
        );

        $pager = new Pagerfanta(new DoctrineORMAdapter($builder));
        $pager->setCurrentPage($request->get('page', 1));

        return $this->render('pages/admin/user/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/user/new", name="user_new", methods={"GET","POST"})
     * @Route("/user/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SecurityMailerService $recovery, User $user = null): Response
    {
        if ($user === null) {
            $user = new User();
        }

        $form = $this->createForm(UserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($user->getId() === null) {
                $em->persist($user);
            }

            $em->flush();

            $this->logger->info(sprintf('New user %s created', $user->getEmail()));

            if (isset($form['invite']) && $form['invite']->getData()) {
                try {
                    $recovery->invite($user);
                } catch (\Exception $e) {
                    $this->addFlash('danger', $this->translator->trans('misc.messages.email_fail'));
                }
            }

            return $this->redirectToRoute('user_index');
        }

        return $this->render('pages/admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}/delete", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($currentUser->getId() === $user->getId()) {
            $this->addFlash('warning', $this->translator->trans('user.messages.misc.deleting_own_user'));

            return $this->redirectToRoute('user_index');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->logger->info(sprintf('User %s deleted', $user->getEmail()));
        }

        return $this->redirectToRoute('user_index');
    }
}
