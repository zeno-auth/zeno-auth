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

namespace ZenoAuth\Web\Infrastructure\Symfony\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use ZenoAuth\Web\Infrastructure\Symfony\Security\AuthenticatedUser;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class RedirectAuthenticatedUserSubscriber
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface       $router
     */
    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event): void
    {
        if ($this->isUserLogged() && $event->isMasterRequest()) {
            $currentRoute = $event->getRequest()->attributes->get('_route');

            if ($this->isOnlyGuestAllowed($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('homepage'));
                $event->setResponse($response);
            }
        }
    }

    private function isUserLogged(): bool
    {
        $user = $this->tokenStorage->getToken()->getUser();

        return $user instanceof AuthenticatedUser;
    }

    private function isOnlyGuestAllowed($currentRoute): bool
    {
        return in_array($currentRoute, ['zeno_auth_login', 'zeno_auth_register']);
    }
}
