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

namespace ZenoAuth\Module\OAuth\Component\Credential\Grant;

use Borobudur\Component\Parameter\ParameterInterface;
use ZenoAuth\Module\OAuth\Component\Credential\Signature;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\OAuth\Domain\Entity\RefreshToken;
use ZenoAuth\Module\OAuth\Domain\Repository\AccessTokenRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\ClientRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\RefreshTokenRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\ScopeRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\TokenIssuerManagerInterface;
use ZenoAuth\Module\OAuth\Exception\OAuthException;
use ZenoAuth\Module\User\Domain\Contract\GrantTypes;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class RefreshTokenCredential extends AbstractCredential
{
    const REFRESH_TOKEN_PARAM = 'refresh_token';

    /**
     * @var RefreshTokenRepositoryInterface
     */
    private $refreshTokenRepository;

    /**
     * @var AccessTokenRepositoryInterface
     */
    private $accessTokenRepository;

    /**
     * Constructor.
     *
     * @param ClientRepositoryInterface       $clientRepository
     * @param ScopeRepositoryInterface        $scopeRepository
     * @param TokenIssuerManagerInterface     $tokenIssuer
     * @param RefreshTokenRepositoryInterface $refreshTokenRepository
     * @param AccessTokenRepositoryInterface  $accessTokenRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository, ScopeRepositoryInterface $scopeRepository, TokenIssuerManagerInterface $tokenIssuer, RefreshTokenRepositoryInterface $refreshTokenRepository, AccessTokenRepositoryInterface $accessTokenRepository)
    {
        parent::__construct($clientRepository, $scopeRepository, $tokenIssuer);

        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return GrantTypes::GRANT_TYPE_REFRESH_TOKEN;
    }

    /**
     * {@inheritdoc}
     */
    protected function sign(Client $client, ParameterInterface $request): Signature
    {
        $refreshToken = $request->get(RefreshTokenCredential::REFRESH_TOKEN_PARAM);
        $refreshToken = $this->findRefreshToken($refreshToken, $client);
        $scopes = (string) $refreshToken->getScopes();

        $newAccessToken = $this->issueAccessToken($refreshToken->getClient(), $refreshToken->getUser(), $scopes);
        $newRefreshToken = $this->issueRefreshToken($newAccessToken);

        $this->revokeRefreshToken($refreshToken);

        return new Signature(
            $newAccessToken, $newRefreshToken, [
                'token_type' => 'Bearer',
                'expires_in' => $newAccessToken->getTtl(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function validate(ParameterInterface $request): void
    {
        parent::validate($request);

        if (false === $request->has(RefreshTokenCredential::REFRESH_TOKEN_PARAM)) {
            throw OAuthException::invalidRequest(RefreshTokenCredential::REFRESH_TOKEN_PARAM);
        }

        try {
            if (false === $this->tokenIssuer->verify($request->get(RefreshTokenCredential::REFRESH_TOKEN_PARAM))) {
                throw OAuthException::invalidToken();
            }
        } catch (\Throwable $e) {
            throw new OAuthException($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * Find refresh token by identified token.
     *
     * @param string $token
     * @param Client $client
     *
     * @return RefreshToken
     * @throws OAuthException
     */
    private function findRefreshToken(string $token, Client $client): RefreshToken
    {
        $identifier = $this->tokenIssuer->identify($token);

        /** @var RefreshToken $refreshToken */
        if (null === $refreshToken = $this->refreshTokenRepository->findOneBy($identifier)) {
            throw OAuthException::invalidToken();
        }

        if (!$client->getId()->equals($refreshToken->getClient()->getId())) {
            throw new OAuthException('Invalid client refresh token', null, 403);
        }

        return $refreshToken;
    }

    /**
     * Revoke refresh token and access token if exist.
     *
     * @param RefreshToken $refreshToken
     */
    private function revokeRefreshToken(RefreshToken $refreshToken): void
    {
        $this->refreshTokenRepository->remove($refreshToken);

        if (null !== $accessToken = $refreshToken->getAccessToken()) {
            $this->accessTokenRepository->remove($accessToken);
        }
    }
}
