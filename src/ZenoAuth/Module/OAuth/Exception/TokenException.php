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

namespace ZenoAuth\Module\OAuth\Exception;

use Borobudur\Component\Exception\Exception;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class TokenException extends Exception
{
    public static function expired(): TokenException
    {
        return new static('The given token has been expired.', null, 401);
    }

    public static function invalidToken(): TokenException
    {
        return new static('The given token is invalid.', null, 401);
    }
}
