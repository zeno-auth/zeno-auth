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

namespace ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Factory;

use ZenoAuth\Module\OAuth\Domain\Entity\Token;
use ZenoAuth\Module\OAuth\Domain\Factory\AccessTokenFactoryInterface;
use ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Entity\AccessToken;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class AccessTokenFactory extends AbstractTokenFactory implements AccessTokenFactoryInterface
{
    protected function getEntity(): Token
    {
        return new AccessToken();
    }
}
