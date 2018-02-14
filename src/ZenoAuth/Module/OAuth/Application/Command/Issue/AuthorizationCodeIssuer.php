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
use ZenoAuth\Module\OAuth\Component\Authorization\Hash\CodeHasherInterface;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\CreateAuthorizationCode;
use ZenoAuth\Module\OAuth\Domain\Entity\AuthorizationCode;
use ZenoAuth\Module\OAuth\Domain\Factory\AuthorizationCodeFactoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\AuthorizationCodeRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\AuthorizationCodeIssuerInterface;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class AuthorizationCodeIssuer implements AuthorizationCodeIssuerInterface
{
    /**
     * @var AuthorizationCodeFactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCodeRepositoryInterface
     */
    private $repository;

    /**
     * @var ClientFinder
     */
    private $clientFinder;

    /**
     * @var UserFinderInterface
     */
    private $userFinder;

    /**
     * @var CodeHasherInterface
     */
    private $codeHasher;

    /**
     * @var int
     */
    private $ttl;

    /**
     * Constructor.
     *
     * @param AuthorizationCodeFactoryInterface    $factory
     * @param AuthorizationCodeRepositoryInterface $repository
     * @param ClientFinder                         $clientFinder
     * @param UserFinderInterface                  $userFinder
     * @param CodeHasherInterface                  $codeHasher
     * @param int                                  $ttl
     */
    public function __construct(AuthorizationCodeFactoryInterface $factory, AuthorizationCodeRepositoryInterface $repository, ClientFinder $clientFinder, UserFinderInterface $userFinder, CodeHasherInterface $codeHasher, $ttl)
    {
        $this->factory = $factory;
        $this->repository = $repository;
        $this->clientFinder = $clientFinder;
        $this->userFinder = $userFinder;
        $this->codeHasher = $codeHasher;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function issue(CreateAuthorizationCode $message): AuthorizationCode
    {
        $ttl = new DateInterval(sprintf('PT%dS', $this->ttl));
        $expiresAt = (new DateTime())->add($ttl);

        $authCode = $this->createAuthCode($message, $expiresAt);
        $authCode->setToken($this->codeHasher->hash($authCode, $expiresAt));

        $this->repository->save($authCode);

        return $authCode;
    }

    /**
     * Create auth code.
     *
     * @param CreateAuthorizationCode $message
     * @param DateTimeInterface       $expiresAt
     *
     * @return AuthorizationCode
     * @throws ModelNotFoundException
     */
    private function createAuthCode(CreateAuthorizationCode $message, DateTimeInterface $expiresAt): AuthorizationCode
    {
        $authCode = $this->factory->create($message);

        $authCode->setClient($this->clientFinder->findOrFail($message->getClient()));
        $authCode->setUser($this->userFinder->findOrFail($message->getUser()));
        $authCode->setTtl($this->ttl);
        $authCode->setExpiresAt($expiresAt);

        return $authCode;
    }
}
