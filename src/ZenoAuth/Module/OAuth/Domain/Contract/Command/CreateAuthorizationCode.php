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

namespace ZenoAuth\Module\OAuth\Domain\Contract\Command;

use ZenoAuth\Shared\Value\Uri;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class CreateAuthorizationCode extends AbstractCreateToken
{
    /**
     * @var string
     */
    private $redirectUri;

    /**
     * @var string
     */
    private $state;

    /**
     * @return Uri
     */
    public function getRedirectUri(): Uri
    {
        return new Uri($this->redirectUri);
    }

    /**
     * @return string
     */
    public function getState(): ?string
    {
        return $this->state;
    }
}
