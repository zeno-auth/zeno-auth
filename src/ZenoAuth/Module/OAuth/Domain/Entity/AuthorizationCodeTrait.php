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

namespace ZenoAuth\Module\OAuth\Domain\Entity;

use ZenoAuth\Shared\Value\Uri;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
trait AuthorizationCodeTrait
{
    /**
     * @var Uri
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $state;

    /**
     * @return Uri
     */
    public function getRedirectUri(): Uri
    {
        return $this->redirectUri;
    }

    /**
     * @param Uri $redirectUri
     */
    public function setRedirectUri(Uri $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(?string $state): void
    {
        $this->state = $state;
    }
}
