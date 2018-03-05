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

namespace ZenoAuth\Api\Module\OAuth\Infrastructure\Symfony\Security;

use Borobudur\Infrastructure\Symfony\Http\Request\BorobudurRequestFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use ZenoAuth\Module\OAuth\Component\Hash\TokenHasherInterface;
use ZenoAuth\Module\OAuth\Component\TokenExtractor\TokenExtractorInterface;
use ZenoAuth\Module\OAuth\Domain\Entity\AccessToken;
use ZenoAuth\Module\OAuth\Domain\Repository\AccessTokenRepositoryInterface;
use ZenoAuth\Module\OAuth\Exception\OAuthException;
use ZenoAuth\Web\Infrastructure\Symfony\Security\AuthenticatedUser;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class OAuthGuard extends AbstractGuardAuthenticator
{
    /**
     * @var TokenExtractorInterface
     */
    private $tokenExtractor;

    /**
     * @var BorobudurRequestFactory
     */
    private $requestFactory;

    /**
     * @var TokenHasherInterface
     */
    private $tokenHasher;

    /**
     * @var AccessTokenRepositoryInterface
     */
    private $accessTokenRepository;

    /**
     * Constructor.
     *
     * @param TokenExtractorInterface        $tokenExtractor
     * @param BorobudurRequestFactory        $requestFactory
     * @param TokenHasherInterface           $tokenHasher
     * @param AccessTokenRepositoryInterface $accessTokenRepository
     */
    public function __construct(TokenExtractorInterface $tokenExtractor, BorobudurRequestFactory $requestFactory, TokenHasherInterface $tokenHasher, AccessTokenRepositoryInterface $accessTokenRepository)
    {
        $this->tokenExtractor = $tokenExtractor;
        $this->requestFactory = $requestFactory;
        $this->tokenHasher = $tokenHasher;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new OAuthException('Authentication Required', null, 401);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return $this->tokenExtractor->supports($this->requestFactory->createRequest($request));
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        return $this->tokenExtractor->extract($this->requestFactory->createRequest($request));
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (null !== $accessToken = $this->loadAccessTokenByCredentials($credentials)) {
            /** @var AuthenticatedUser $user */
            $user = $userProvider->loadUserByUsername((string) $accessToken->getUser()->getUsername());

            return new OAuthUser(
                $user,
                $accessToken->getScopes()
            );
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->tokenHasher->verify($credentials);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new OAuthException('Unauthenticated', null, 401);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * @param string $credentials
     *
     * @return AccessToken
     */
    private function loadAccessTokenByCredentials(string $credentials): ?AccessToken
    {
        $criteria = $this->tokenHasher->getIdentifier($credentials);

        return $this->accessTokenRepository->findOneBy($criteria);
    }
}
