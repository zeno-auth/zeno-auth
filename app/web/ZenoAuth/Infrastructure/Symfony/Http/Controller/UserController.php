<?php
/**
 * This file is part of the Zeno Auth package.
 *
 * (c) 2018 Borobudur <http://borobudur.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ZenoAuth\Web\Infrastructure\Symfony\Http\Controller;

use Borobudur\Component\Messaging\Bus\MessageBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use ZenoAuth\Module\User\Domain\Contract\Command\ChangeUserPassword;
use ZenoAuth\Module\User\Domain\Contract\Command\UpdateUser;

/**
 * @Route(service="ZenoAuth\Web\Infrastructure\Symfony\Http\Controller\UserController")
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class UserController extends Controller
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * Constructor.
     *
     * @param MessageBusInterface $bus
     */
    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function profile(): Response
    {
        return $this->render(
            '@ZenoAuthWeb/user/pages/profile.page.html.twig',
            [
                'user' => $this->getUser()->getUser(),
            ]
        );
    }

    /**
     * @Route("/users/{user}", name="zeno_auth_update_user")
     * @Method("POST")
     *
     * @param string $user
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateProfile(string $user, Request $request): JsonResponse
    {
        $token = $request->get('_csrf_token');

        if (!$this->isCsrfTokenValid('profile', $token)) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }

        $message = new UpdateUser(
            array_merge(
                ['id' => $user],
                $request->request->all()
            )
        );

        $this->bus->dispatch($message);

        return new JsonResponse($message->getMessagePayload()->all());
    }

    /**
     * @Route("/settings/security", name="zeno_auth_security_settings")
     * @Method("GET")
     *
     * @return Response
     */
    public function security(): Response
    {
        return $this->render('@ZenoAuthWeb/user/pages/security.page.html.twig', [
            'user' => $this->getUser()->getUser(),
        ]);
    }

    /**
     * @Route("/users/{user}/change-password", name="zeno_auth_user_change_password")
     * @Method("POST")
     *
     * @param string $user
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function changePassword(string $user, Request $request): JsonResponse
    {
        try {
            $token = $request->get('_csrf_token');

            if (!$this->isCsrfTokenValid('security', $token)) {
                throw new InvalidCsrfTokenException('Invalid CSRF token.');
            }

            $message = new ChangeUserPassword(
                array_merge(
                    ['user' => $user],
                    $request->request->all()
                )
            );

            $this->bus->dispatch($message);

            return new JsonResponse(['id' => $message->getUser()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getPrevious()->getMessage()], 400);
        }
    }
}
