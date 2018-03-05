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
use ZenoAuth\Module\OAuth\Domain\Repository\ClientRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\ScopeRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\TokenIssuerManagerInterface;
use ZenoAuth\Module\OAuth\Exception\OAuthException;
use ZenoAuth\Module\User\Domain\Contract\GrantTypes;
use ZenoAuth\Module\User\Domain\Entity\User;
use ZenoAuth\Module\User\Domain\Repository\UserRepositoryInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class PasswordCredential extends AbstractCredential
{
    const USERNAME_PARAM = 'username';

    const PASSWORD_PARAM = 'password';

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * Constructor.
     *
     * @param ClientRepositoryInterface   $clientRepository
     * @param ScopeRepositoryInterface    $scopeRepository
     * @param TokenIssuerManagerInterface $tokenIssuer
     * @param UserRepositoryInterface     $userRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository, ScopeRepositoryInterface $scopeRepository, TokenIssuerManagerInterface $tokenIssuer, UserRepositoryInterface $userRepository)
    {
        parent::__construct($clientRepository, $scopeRepository, $tokenIssuer);

        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return GrantTypes::GRANT_TYPE_USER_CREDENTIALS;
    }

    /**
     * {@inheritdoc}
     */
    protected function sign(Client $client, ParameterInterface $request): Signature
    {
        $username = $request->get(PasswordCredential::USERNAME_PARAM);
        $password = $request->get(PasswordCredential::PASSWORD_PARAM);
        $scopes = $request->get('scopes', 'basic');
        $user = $this->findUser($username, $password);

        $accessToken = $this->issueAccessToken($client, $user, $scopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

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
    protected function validate(ParameterInterface $request): void
    {
        parent::validate($request);

        if (false === $request->has(PasswordCredential::USERNAME_PARAM)) {
            throw OAuthException::invalidRequest(PasswordCredential::USERNAME_PARAM);
        }

        if (false === $request->has(PasswordCredential::PASSWORD_PARAM)) {
            throw OAuthException::invalidRequest(PasswordCredential::PASSWORD_PARAM);
        }
    }

    /**
     * Find user by username and check the given password is valid.
     *
     * @param string $username
     * @param string $password
     *
     * @return User
     * @throws OAuthException
     */
    private function findUser(string $username, string $password): User
    {
        if (null === $user = $this->userRepository->findUserByUsernameOrEmail($username)) {
            throw OAuthException::invalidUser();
        }

        if (false === $user->getPassword()->matches($password)) {
            throw OAuthException::invalidUser();
        }

        return $user;
    }
}
