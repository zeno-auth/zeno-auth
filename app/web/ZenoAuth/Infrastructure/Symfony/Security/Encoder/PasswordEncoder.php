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

namespace ZenoAuth\Web\Infrastructure\Symfony\Security\Encoder;

use Borobudur\Component\Value\User\Password\Password;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class PasswordEncoder implements PasswordEncoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function encodePassword($raw, $salt)
    {
        return $raw;
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        if ($encoded instanceof Password) {
            return $encoded->matches($raw);
        }

        return false;
    }
}
