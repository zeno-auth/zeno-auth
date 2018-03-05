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
use ZenoAuth\Module\OAuth\Component\Authorization\Hash\CodeHasherInterface;
use ZenoAuth\Module\OAuth\Component\Credential\Signature;
use ZenoAuth\Module\OAuth\Domain\Entity\AuthorizationCode;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\OAuth\Domain\Repository\AuthorizationCodeRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\ClientRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\ScopeRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\TokenIssuerManagerInterface;
use ZenoAuth\Module\OAuth\Exception\OAuthException;
use ZenoAuth\Module\User\Domain\Contract\GrantTypes;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class AuthorizationCodeCredential extends AbstractCredential
{
    const AUTH_CODE_PARAM = 'code';

    /**
     * @var AuthorizationCodeRepositoryInterface
     */
    private $authCodeRepository;

    /**
     * @var CodeHasherInterface
     */
    private $codeHasher;

    /**
     * Constructor.
     *
     * @param ClientRepositoryInterface            $clientRepository
     * @param ScopeRepositoryInterface             $scopeRepository
     * @param TokenIssuerManagerInterface          $tokenIssuer
     * @param AuthorizationCodeRepositoryInterface $authCodeRepository
     * @param CodeHasherInterface                  $codeHasher
     */
    public function __construct(ClientRepositoryInterface $clientRepository, ScopeRepositoryInterface $scopeRepository, TokenIssuerManagerInterface $tokenIssuer, AuthorizationCodeRepositoryInterface $authCodeRepository, CodeHasherInterface $codeHasher)
    {
        parent::__construct($clientRepository, $scopeRepository, $tokenIssuer);

        $this->codeHasher = $codeHasher;
        $this->authCodeRepository = $authCodeRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function sign(Client $client, ParameterInterface $request): Signature
    {
        $authCode = $this->findAuthCode($request);

        if (!$authCode->getClient()->getId()->equals($client->getId())) {
            throw OAuthException::invalidClient();
        }

        $client = $authCode->getClient();
        $user = $authCode->getUser();
        $scopes = (string) $authCode->getScopes();

        $accessToken = $this->issueAccessToken($client, $user, $scopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

        $this->revokeAuthCode($authCode);

        return new Signature(
            $accessToken, $refreshToken, [
                'token_type' => 'Bearer',
                'expires_in' => $accessToken->getTtl(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return GrantTypes::GRANT_TYPE_AUTH_CODE;
    }

    /**
     * {@inheritdoc}
     */
    protected function validate(ParameterInterface $request): void
    {
        parent::validate($request);

        if (false === $request->has(AuthorizationCodeCredential::AUTH_CODE_PARAM)) {
            throw OAuthException::invalidRequest(AuthorizationCodeCredential::AUTH_CODE_PARAM);
        }

        if (false === $request->has('redirect_uri')) {
            throw OAuthException::invalidRequest('redirect_uri');
        }
    }

    /**
     * @param ParameterInterface $request
     *
     * @return AuthorizationCode
     * @throws OAuthException
     */
    private function findAuthCode(ParameterInterface $request): AuthorizationCode
    {
        $authCode = $request->get(AuthorizationCodeCredential::AUTH_CODE_PARAM);

        if (false === $this->codeHasher->verify($authCode)) {
            throw OAuthException::invalidAuthCode();
        }

        /** @var AuthorizationCode $authCode */
        if (null === $authCode = $this->authCodeRepository->findOneBy($this->codeHasher->getIdentifier($authCode))) {
            throw OAuthException::invalidAuthCode();
        }

        if ($request->get('redirect_uri') !== (string) $authCode->getRedirectUri()) {
            throw new OAuthException('Forbidden redirect uri.', [], 403);
        }

        if ($authCode->getState() !== $request->get('state')) {
            throw new OAuthException('Invalid state.', [], 401);
        }

        return $authCode;
    }

    /**
     * @param AuthorizationCode $authCode
     */
    private function revokeAuthCode(AuthorizationCode $authCode): void
    {
        $this->authCodeRepository->remove($authCode);
    }
}
