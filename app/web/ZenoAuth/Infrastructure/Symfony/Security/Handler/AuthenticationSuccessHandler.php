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

namespace ZenoAuth\Web\Infrastructure\Symfony\Security\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    use TargetPathTrait;

    /**
     * @var string
     */
    protected $providerKey;

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        $redirectUri = $this->getTargetPath($request->getSession(), $this->providerKey);

        if (null !== $redirectUri) {
            $this->removeTargetPath($request->getSession(), $this->providerKey);
        }

        return new JsonResponse(
            [
                'user'         => $token->getUser(),
                'redirect_uri' => $redirectUri,
            ]
        );
    }

    /**
     * Get the provider key.
     *
     * @return string
     */
    public function getProviderKey()
    {
        return $this->providerKey;
    }

    /**
     * Set the provider key.
     *
     * @param string $providerKey
     */
    public function setProviderKey($providerKey)
    {
        $this->providerKey = $providerKey;
    }
}
