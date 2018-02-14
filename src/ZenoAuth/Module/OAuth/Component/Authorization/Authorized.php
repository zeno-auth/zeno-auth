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

namespace ZenoAuth\Module\OAuth\Component\Authorization;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class Authorized
{
    /**
     * @var string
     */
    private $redirectUri;

    /**
     * @var array
     */
    private $params;

    /**
     * Constructor.
     *
     * @param string $redirectUri
     * @param array  $params
     */
    public function __construct(string $redirectUri, array $params)
    {
        $this->redirectUri = $redirectUri;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
