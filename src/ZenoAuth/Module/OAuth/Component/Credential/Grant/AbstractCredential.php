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

use Borobudur\Component\Ddd\Exception\ModelNotFoundException;
use Borobudur\Component\Parameter\ParameterInterface;
use ZenoAuth\Module\OAuth\Application\Command\Issue\CreateAccessToken;
use ZenoAuth\Module\OAuth\Application\Command\Issue\CreateRefreshToken;
use ZenoAuth\Module\OAuth\Component\Credential\Signature;
use ZenoAuth\Module\OAuth\Domain\Entity\AccessToken;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\OAuth\Domain\Entity\ClientId;
use ZenoAuth\Module\OAuth\Domain\Entity\RefreshToken;
use ZenoAuth\Module\OAuth\Domain\Model\ScopeTransformerTrait;
use ZenoAuth\Module\OAuth\Domain\Repository\ClientRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\ScopeRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\TokenIssuerManagerInterface;
use ZenoAuth\Module\OAuth\Exception\OAuthException;
use ZenoAuth\Module\User\Domain\Entity\User;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
abstract class AbstractCredential implements CredentialInterface
{
    use ScopeTransformerTrait;

    /**
     * @var bool
     */
    protected $needClientSecret = true;

    /**
     * @var ClientRepositoryInterface
     */
    protected $clientRepository;

    /**
     * @var TokenIssuerManagerInterface
     */
    protected $tokenIssuer;

    /**
     * @var ScopeRepositoryInterface
     */
    private $scopeRepository;

    /**
     * Constructor.
     *
     * @param ClientRepositoryInterface   $clientRepository
     * @param ScopeRepositoryInterface    $scopeRepository
     * @param TokenIssuerManagerInterface $tokenIssuer
     */
    public function __construct(ClientRepositoryInterface $clientRepository, ScopeRepositoryInterface $scopeRepository, TokenIssuerManagerInterface $tokenIssuer)
    {
        $this->clientRepository = $clientRepository;
        $this->scopeRepository = $scopeRepository;
        $this->tokenIssuer = $tokenIssuer;
    }

    /**
     * {@inheritdoc}
     */
    public function grant(ParameterInterface $request): Signature
    {
        $this->validate($request);

        return $this->sign($this->findClient($request), $request);
    }

    /**
     * Validate request.
     *
     * @param ParameterInterface $request
     *
     * @throws OAuthException
     */
    protected function validate(ParameterInterface $request): void
    {
        if (false === $request->has('client_id')) {
            throw OAuthException::invalidRequest('client_id');
        }

        if (true === $this->needClientSecret && false === $request->has('client_secret')) {
            throw OAuthException::invalidRequest('client_secret');
        }
    }

    /**
     * Find and validate client.
     *
     * @param ParameterInterface $request
     *
     * @return Client
     * @throws OAuthException
     */
    protected function findClient(ParameterInterface $request): Client
    {
        /** @var Client $client */
        $client = $this->clientRepository->find(new ClientId($request->get('client_id')));

        if (null === $client) {
            throw OAuthException::invalidClient();
        }

        if (true === $this->needClientSecret && $client->getSecret() !== $request->get('client_secret')) {
            throw OAuthException::invalidClient();
        }

        if (!in_array($this->getName(), $client->getAllowedGrantTypes(), true)) {
            throw OAuthException::invalidGrantType($this->getName());
        }

        return $client;
    }

    /**
     * Issue access token.
     *
     * @param Client $client
     * @param User   $user
     * @param string $scopes
     *
     * @return AccessToken
     * @throws ModelNotFoundException
     */
    protected function issueAccessToken(Client $client, User $user, string $scopes): AccessToken
    {
        return $this->tokenIssuer->issue(
            new CreateAccessToken(
                [
                    'client' => (string) $client->getId(),
                    'user'   => (string) $user->getId(),
                    'scopes' => $this->getScopes($scopes),
                ]
            )
        );
    }

    /**
     * Issue refresh token.
     *
     * @param AccessToken $accessToken
     *
     * @return RefreshToken
     * @throws ModelNotFoundException
     */
    protected function issueRefreshToken(AccessToken $accessToken): RefreshToken
    {
        return $this->tokenIssuer->issue(
            new CreateRefreshToken(
                [
                    'access_token' => (string) $accessToken->getId(),
                    'client'       => (string) $accessToken->getClient()->getId(),
                    'user'         => (string) $accessToken->getUser()->getId(),
                    'scopes'       => $this->getScopes((string) $accessToken->getScopes()),
                ]
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getScopeRepository(): ScopeRepositoryInterface
    {
        return $this->scopeRepository;
    }

    /**
     * Create a signature.
     *
     * @param Client             $client
     * @param ParameterInterface $request
     *
     * @return Signature
     */
    abstract protected function sign(Client $client, ParameterInterface $request): Signature;
}
