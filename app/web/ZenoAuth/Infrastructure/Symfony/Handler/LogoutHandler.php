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

namespace ZenoAuth\Web\Infrastructure\Symfony\Handler;

use Borobudur\Component\Messaging\Bus\MessageBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\RevokeCredential;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class LogoutHandler implements LogoutHandlerInterface
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
     * {@inheritdoc}
     */
    public function logout(Request $request, Response $response, TokenInterface $token): void
    {
        $this->bus->dispatch(new RevokeCredential(['user' => (string) $token->getUser()->getUser()->getId()]));
    }
}
