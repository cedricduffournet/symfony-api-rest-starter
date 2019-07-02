<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Type\ResettingFormType;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\GroupManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Public controller.
 *
 * @author Cedric DUFFOURNET <contact@cedricduffournet.com>
 */
class PublicController extends AbstractFOSRestController
{
    private $eventDispatcher;
    private $userManager;
    private $groupManager;
    private $mailer;
    private $tokenGenerator;
    private $retryTtl;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        UserManagerInterface $userManager,
        GroupManagerInterface $groupManager,
        MailerInterface $mailer,
        TokenGeneratorInterface $tokenGenerator,
        int $retryTtl)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userManager = $userManager;
        $this->groupManager = $groupManager;
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
        $this->retryTtl = $retryTtl;
    }

    /**
     * Send email for password resetting.
     *
     * @Operation(
     *     tags={"Public"},
     *     summary="Send email for password resetting.",
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="Username",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="confirmationUrl",
     *         in="formData",
     *         description="Wich url validate confirmation",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when the form has errors"
     *     )
     * )
     *
     * @Rest\Post("/public/reset/mail")
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|RouteRedirectView
     */
    public function postResetMailAction(Request $request): View
    {
        $username = $request->request->get('username');
        /** @var $user UserInterface */
        $user = $this->userManager->findUserByUsernameOrEmail($username);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find user entity.');
        }

        /* Dispatch init event */
        $event = new GetResponseNullableUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        if (!$user->isPasswordRequestNonExpired($this->retryTtl)) {
            $reset = $this->resetMail($user, $request);
            if (0 !== $reset) {
                return $event->getResponse();
            }

            return $this->view('mailSended', Response::HTTP_OK);
        }

        throw new BadRequestHttpException('mailAlreadySended');
    }

    /**
     * Check user token.
     *
     * @Rest\Get("/public/reset/confirm/{token}")
     * @Operation(
     *     tags={"Public"},
     *     summary="Check user token.",
     *     @SWG\Parameter(
     *         name="token",
     *         in="path",
     *         description="Token sent in url",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when the User is not found"
     *     )
     * )
     *
     * @throws NotFoundHttpException when User not exist
     */
    public function getResetConfirmAction(string $token): View
    {
        $user = $this->userManager->findUserByConfirmationToken($token);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find user entity.');
        }

        return $this->view('resetConfirmed', Response::HTTP_OK);
    }

    /**
     * Rest User password.
     *
     * @Operation(
     *     tags={"Public"},
     *     summary="Rest User password.",
     *     @SWG\Parameter(
     *         name="token",
     *         in="query",
     *         description="Token sent in url",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="Request body",
     *         in="body",
     *         description="Password",
     *         required=true,
     *         @Model(type=FOS\UserBundle\Form\Type\ResettingFormType::class)
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when the form has errors"
     *     )
     * )
     *
     * @param Request $request the request object
     * @Rest\Post("/public/reset/{token}")
     *
     * @return FormTypeInterface|RouteRedirectView
     */
    public function postResetAction(Request $request, string $token): View
    {
        $user = $this->userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw $this->createNotFoundException('Unable to find user.');
        }

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        $form = $this->createForm(ResettingFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

            $this->userManager->updateUser($user);

            return $this->view('passwordReseted', Response::HTTP_OK);
        }

        return $this->view($form);
    }

    /**
     * Register user.
     *
     * @Rest\Post("/public/register")
     * @Operation(
     *     tags={"Public"},
     *     @SWG\Parameter(
     *         name="Request body",
     *         in="body",
     *         description="User that need to be added",
     *         required=true,
     *         @Model(type=App\Form\UserType::class)
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Returned when created",
     *         @Model(type=App\Entity\User::class, groups={"Default","user_info"})
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when the form has errors"
     *     )
     * )
     */
    public function postRegisterAction(Request $request): View
    {
        $entity = $this->userManager->createUser();
        $form = $this->createForm(UserType::class, $entity);
        $form->submit($request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $dispatcher EventDispatcherInterface */
            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
            $this->userManager->updateUser($entity);
            $defaultGroup = $this->groupManager->findGroupBy(['defaultGroup' => true]);
            $entity->addGroup($defaultGroup);

            return $this->view($entity, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Confirm registration link.
     *
     * @Rest\Get("/public/register/confirm/{token}")
     * @Operation(
     *     tags={"Public"},
     *     summary="Register confirmation.",
     *     @SWG\Parameter(
     *         name="token",
     *         in="path",
     *         description="Token sent in url",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when the User is not found"
     *     )
     * )
     *
     * @throws NotFoundHttpException when User not exist
     */
    public function getRegisterConfirmAction(Request $request, $token): View
    {
        $user = $this->userManager->findUserByConfirmationToken($token);
        if (!$user) {
            throw $this->createNotFoundException('Unable to find user entity.');
        }
        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);
        $this->userManager->updateUser($user);

        return $this->view('registerConfirmed', Response::HTTP_OK);
    }

    private function resetMail(User $user, Request $request)
    {
        $event = $this->resetMailRequest($user, $request);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $event = $this->resetMailConfirm($user, $request);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        $event = $this->resetMailComplete($user, $request);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        return 0;
    }

    private function resetMailRequest(User $user, Request $request): GetResponseUserEvent
    {
        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_REQUEST, $event);

        return $event;
    }

    private function resetMailConfirm(User $user, Request $request): GetResponseUserEvent
    {
        if (null === $user->getConfirmationToken()) {
            /* @var $tokenGenerator TokenGeneratorInterface */
            $user->setConfirmationToken($this->tokenGenerator->generateToken());

            $confirmationUrl = $request->request->get('confirmationUrl');
            $user->setConfirmationUrl($confirmationUrl);
        }
        /* Dispatch confirm event */
        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_CONFIRM, $event);

        return $event;
    }

    private function resetMailComplete(User $user, Request $request): GetResponseUserEvent
    {
        $this->mailer->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->userManager->updateUser($user);

        /* Dispatch completed event */
        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_COMPLETED, $event);

        return $event;
    }
}
