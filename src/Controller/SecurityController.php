<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * User service.
     */
    private UserServiceInterface $userService;

    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;
    /**
     * Constructor.
     *
     * @param UserServiceInterface        $userService
     * @param TranslatorInterface         $translator
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userService = $userService;
        $this->translator = $translator;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Log in.
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('question_index');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Log out.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Edit action.
     *
     * @param Request                     $request
     * @param User                        $user
     * @param UserPasswordHasherInterface $passwordHasher
     *
     * @return Response
     */
    #[Route('/{id}/account_edit', name: 'app_account_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]

    public function edit(Request $request, User $user, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(
            UserType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('app_account_edit', ['id' => $user->getId()]),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            $this->userService->save($user);

            $this->addFlash('success','message_updated_successfully');
            return $this->redirectToRoute('question_index');
        }

        return $this->render(
            'security/edit.html.twig',
            ['form' => $form->createView()]
        );
    }
}
