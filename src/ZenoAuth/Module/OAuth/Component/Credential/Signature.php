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

namespace ZenoAuth\Module\OAuth\Component\Credential;

use ZenoAuth\Module\OAuth\Domain\Entity\AccessToken;
use ZenoAuth\Module\OAuth\Domain\Entity\RefreshToken;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class Signature
{
    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * @var RefreshToken
     */
    private $refreshToken;

    /**
     * @var array
     */
    private $attributes;

    /**
     * Constructor.
     *
     * @param AccessToken  $accessToken
     * @param RefreshToken $refreshToken
     * @param array        $attributes
     */
    public function __construct(AccessToken $accessToken, RefreshToken $refreshToken = null, array $attributes = [])
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->attributes = $attributes;
    }

    /**
     * @return AccessToken
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    /**
     * @return RefreshToken
     */
    public function getRefreshToken(): ?RefreshToken
    {
        return $this->refreshToken;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function payload(): array
    {
        $payload = array_merge($this->attributes, ['access_token' => $this->accessToken->getToken()]);

        if (null !== $this->refreshToken) {
            $payload['refresh_token'] = $this->refreshToken->getToken();
        }

        return $payload;
    }
}
