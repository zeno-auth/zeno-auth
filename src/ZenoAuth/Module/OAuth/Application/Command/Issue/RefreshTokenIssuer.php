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

use DateTimeInterface;
use RuntimeException;
use ZenoAuth\Module\OAuth\Application\Query\ClientFinder;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\AbstractCreateToken;
use ZenoAuth\Module\OAuth\Domain\Entity\RefreshToken;
use ZenoAuth\Module\OAuth\Domain\Entity\Token;
use ZenoAuth\Module\OAuth\Domain\Factory\RefreshTokenFactoryInterface;
use ZenoAuth\Module\OAuth\Domain\Factory\TokenFactoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\AccessTokenRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\RefreshTokenRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\TokenRepositoryInterface;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class RefreshTokenIssuer extends AbstractTokenIssuer
{
    /**
     * @var RefreshTokenRepositoryInterface
     */
    private $refreshTokenRepository;

    /**
     * @var RefreshTokenFactoryInterface
     */
    private $factory;

    /**
     * @var AccessTokenRepositoryInterface
     */
    private $accessTokenRepository;

    /**
     * @var int
     */
    private $ttl;

    /**
     * Constructor.
     *
     * @param ClientFinder                    $clientFinder
     * @param UserFinderInterface             $userFinder
     * @param RefreshTokenRepositoryInterface $refreshTokenRepository
     * @param RefreshTokenFactoryInterface    $factory
     * @param AccessTokenRepositoryInterface  $accessTokenRepository
     * @param int                             $ttl
     */
    public function __construct(ClientFinder $clientFinder, UserFinderInterface $userFinder, RefreshTokenRepositoryInterface $refreshTokenRepository, RefreshTokenFactoryInterface $factory, AccessTokenRepositoryInterface $accessTokenRepository, int $ttl)
    {
        parent::__construct($clientFinder, $userFinder);

        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->factory = $factory;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($message): bool
    {
        return $message instanceof CreateRefreshToken;
    }

    /**
     * {@inheritdoc}
     */
    protected function createToken(AbstractCreateToken $message, DateTimeInterface $expiresAt): Token
    {
        if ($message instanceof CreateRefreshToken) {
            /** @var RefreshToken $token */
            $token = parent::createToken($message, $expiresAt);
            $token->setAccessToken($this->accessTokenRepository->find($message->getAccessToken()));

            return $token;
        }

        throw new RuntimeException(
            sprintf(
                'Parameter "$message" should instance of "%s"',
                CreateRefreshToken::class
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFactory(): TokenFactoryInterface
    {
        return $this->factory;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRepository(): TokenRepositoryInterface
    {
        return $this->refreshTokenRepository;
    }

    protected function getTokenTtl(): int
    {
        return $this->ttl;
    }
}
