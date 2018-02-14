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

namespace ZenoAuth\Module\OAuth\Application\Command\Issue;

use Borobudur\Component\Ddd\Exception\ModelNotFoundException;
use DateInterval;
use DateTime;
use DateTimeInterface;
use ZenoAuth\Module\OAuth\Application\Query\ClientFinder;
use ZenoAuth\Module\OAuth\Component\Hash\TokenHasherInterface;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\AbstractCreateToken;
use ZenoAuth\Module\OAuth\Domain\Entity\Token;
use ZenoAuth\Module\OAuth\Domain\Factory\TokenFactoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\TokenRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\TokenIssuerInterface;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
abstract class AbstractTokenIssuer implements TokenIssuerInterface
{
    /**
     * @var ClientFinder
     */
    protected $clientFinder;

    /**
     * @var UserFinderInterface
     */
    protected $userFinder;

    /**
     * Constructor.
     *
     * @param ClientFinder        $clientFinder
     * @param UserFinderInterface $userFinder
     */
    public function __construct(ClientFinder $clientFinder, UserFinderInterface $userFinder)
    {
        $this->clientFinder = $clientFinder;
        $this->userFinder = $userFinder;
    }

    /**
     * {@inheritdoc}
     */
    public final function issue(AbstractCreateToken $message, TokenHasherInterface $tokenHasher): Token
    {
        $ttl = new DateInterval(sprintf('PT%dS', $this->getTokenTtl()));
        $expiresAt = (new DateTime())->add($ttl);

        $token = $this->createToken($message, $expiresAt);
        $token->setToken($tokenHasher->hash($token, $expiresAt));

        $this->getRepository()->save($token);

        return $token;
    }

    /**
     * Create a token.
     *
     * @param AbstractCreateToken $message
     * @param DateTimeInterface   $expiresAt
     *
     * @return Token
     * @throws ModelNotFoundException
     */
    protected function createToken(AbstractCreateToken $message, DateTimeInterface $expiresAt): Token
    {
        $token = $this->getFactory()->create($message);

        $token->setClient($this->clientFinder->findOrFail($message->getClient()));
        $token->setUser($this->userFinder->findOrFail($message->getUser()));
        $token->setTtl($this->getTokenTtl());
        $token->setExpiresAt($expiresAt);

        return $token;
    }

    /**
     * Get token factory.
     *
     * @return TokenFactoryInterface
     */
    abstract protected function getFactory(): TokenFactoryInterface;

    /**
     * Get token repository.
     *
     * @return TokenRepositoryInterface
     */
    abstract protected function getRepository(): TokenRepositoryInterface;

    /**
     * Get token TTL.
     *
     * @return int
     */
    abstract protected function getTokenTtl(): int;
}
