<?php
/**
 * This file is part of the zeno-auth package.
 *
 * (c) 2018 Borobudur <http://borobudur.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ZenoAuth\Shared\Util;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class Random
{
    public static function generateToken(int $length = 50): string
    {
        if (@file_exists('/dev/urandom')) {
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 100) . uniqid((string) mt_rand(), true);
        } else {
            $rand = mt_rand() . mt_rand() . mt_rand() . mt_rand();
            $randomData = $rand . microtime(true) . uniqid((string) mt_rand(), true);
        }

        return substr(hash('sha512', $randomData), 0, $length);
    }
}
