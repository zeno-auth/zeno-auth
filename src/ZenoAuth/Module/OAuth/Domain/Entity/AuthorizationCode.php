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
interface AuthorizationCode extends Token
{
    public function setRedirectUri(Uri $redirectUri): void;

    public function getRedirectUri(): Uri;

    public function setState(?string $state): void;

    public function getState(): ?string;
}
