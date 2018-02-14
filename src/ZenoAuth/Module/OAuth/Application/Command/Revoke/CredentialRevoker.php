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

namespace ZenoAuth\Module\OAuth\Application\Command\Revoke;

use ZenoAuth\Module\OAuth\Domain\Contract\Command\RevokeCredential;
use ZenoAuth\Module\OAuth\Domain\Repository\AccessTokenRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\RefreshTokenRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\CredentialRevokerInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class CredentialRevoker implements CredentialRevokerInterface
{
    /**
     * @var AccessTokenRepositoryInterface
     */
    private $accessTokenRepository;

    /**
     * @var RefreshTokenRepositoryInterface
     */
    private $refreshTokenRepository;

    /**
     * Constructor.
     *
     * @param AccessTokenRepositoryInterface  $accessTokenRepository
     * @param RefreshTokenRepositoryInterface $refreshTokenRepository
     */
    public function __construct(AccessTokenRepositoryInterface $accessTokenRepository, RefreshTokenRepositoryInterface $refreshTokenRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    public function revoke(RevokeCredential $message): void
    {
        $this->refreshTokenRepository->removeByUser($message->getUser());
        $this->accessTokenRepository->removeByUser($message->getUser());
    }
}
