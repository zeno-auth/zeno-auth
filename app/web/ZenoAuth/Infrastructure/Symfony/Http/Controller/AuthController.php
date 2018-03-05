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
use RuntimeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use ZenoAuth\Module\User\Domain\Contract\Command\CreateUser;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;
use ZenoAuth\Web\Infrastructure\Symfony\Security\AuthenticatedUser;

/**
 * @Route(service="ZenoAuth\Web\Infrastructure\Symfony\Http\Controller\AuthController")
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class AuthController extends Controller
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var UserFinderInterface
     */
    private $userFinder;

    public function __construct(MessageBusInterface $bus, TokenStorageInterface $tokenStorage, UserFinderInterface $userFinder)
    {
        $this->bus = $bus;
        $this->tokenStorage = $tokenStorage;
        $this->userFinder = $userFinder;
    }

    /**
     * @Route("/auth/login", name="zeno_auth_login")
     * @Method("GET")
     */
    public function login(): Response
    {
        return $this->render('@ZenoAuthWeb/auth/pages/login.page.html.twig');
    }

    /**
     * @Route("/auth/login", name="zeno_auth_check_login")
     */
    public function checkLogin()
    {
        throw new RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Route("/auth/logout", name="zeno_auth_logout")
     */
    public function logout()
    {
        throw new RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * @Route("/auth/register", name="zeno_auth_register")
     * @Method("GET")
     */
    public function registerAction(): Response
    {
        return $this->render('@ZenoAuthWeb/auth/pages/register.page.html.twig');
    }

    /**
     * @Route("/auth/register", name="zeno_auth_submit_register")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function submitRegister(Request $request): JsonResponse
    {
        try {
            $content = $request->getContent();
            $data = json_decode($content, true);
            $token = isset($data['_csrf_token']) ? $data['_csrf_token'] : null;

            if (!$this->isCsrfTokenValid('register', $token)) {
                throw new InvalidCsrfTokenException('Invalid CSRF token.');
            }

            $message = new CreateUser($data);

            $this->bus->dispatch($message);

            $payload = $message->getMessagePayload()->all();
            $payload = array_merge(['id' => (string) $message->getId()], $payload);

            $user = new AuthenticatedUser($this->userFinder->findOrFail($message->getId()));
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

            $this->tokenStorage->setToken($token);

            $request->getSession()->set('_security_main', serialize($token));

            return new JsonResponse($payload, 201);
        } catch (\Exception $e) {
            if (null !== $previous = $e->getPrevious()) {
                $message = $previous->getMessage();
            } else {
                $message = $e->getMessage();
            }

            return new JsonResponse(['error' => $message], 400);
        }
    }
}
