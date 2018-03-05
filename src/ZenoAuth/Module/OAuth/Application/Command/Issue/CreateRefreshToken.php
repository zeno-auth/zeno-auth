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

namespace ZenoAuth\Module\OAuth\Application\Command\Issue;

use ZenoAuth\Module\OAuth\Domain\Contract\Command\AbstractCreateToken;
use ZenoAuth\Module\OAuth\Domain\Entity\TokenId;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class CreateRefreshToken extends AbstractCreateToken
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @return TokenId
     */
    public function getAccessToken(): TokenId
    {
        return new TokenId($this->accessToken);
    }
}
