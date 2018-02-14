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

namespace ZenoAuth\Module\OAuth\Component\Authorization\ResponseType;

use Borobudur\Component\Parameter\ParameterInterface;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\CreateAuthorizationCode;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\OAuth\Domain\Model\ScopeTransformerTrait;
use ZenoAuth\Module\OAuth\Domain\Repository\ScopeRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\AuthorizationCodeIssuerInterface;
use ZenoAuth\Module\OAuth\Exception\OAuthException;
use ZenoAuth\Module\User\Domain\Contract\GrantTypes;
use ZenoAuth\Module\User\Domain\Entity\User;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class AuthorizationCodeResponseType implements ResponseTypeInterface
{
    use ScopeTransformerTrait;

    const RESPONSE_TYPE_NAME = 'code';

    /**
     * @var AuthorizationCodeIssuerInterface
     */
    private $authCodeIssuer;

    /**
     * @var ScopeRepositoryInterface
     */
    private $scopeRepository;

    /**
     * Constructor.
     *
     * @param AuthorizationCodeIssuerInterface $authCodeIssuer
     * @param ScopeRepositoryInterface         $scopeRepository
     */
    public function __construct(AuthorizationCodeIssuerInterface $authCodeIssuer, ScopeRepositoryInterface $scopeRepository)
    {
        $this->authCodeIssuer = $authCodeIssuer;
        $this->scopeRepository = $scopeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function respond(Client $client, User $user, ParameterInterface $request): array
    {
        if (!in_array(GrantTypes::GRANT_TYPE_AUTH_CODE, $client->getAllowedGrantTypes(), true)) {
            throw OAuthException::invalidGrantType(GrantTypes::GRANT_TYPE_IMPLICIT);
        }

        $state = $request->get('state');
        $authCode = $this->authCodeIssuer->issue(
            new CreateAuthorizationCode(
                [
                    'client'       => (string) $client->getId(),
                    'user'         => (string) $user->getId(),
                    'scopes'       => $this->getScopes($request->get('scopes', 'basic')),
                    'state'        => $state,
                    'redirect_uri' => $request->get('continue'),
                ]
            )
        );

        return [
            'code'  => $authCode->getToken(),
            'state' => $state,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return AuthorizationCodeResponseType::RESPONSE_TYPE_NAME;
    }

    /**
     * {@inheritdoc}
     */
    protected function getScopeRepository(): ScopeRepositoryInterface
    {
        return $this->scopeRepository;
    }
}
