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

namespace ZenoAuth\Module\OAuth\Component\Hash;

use DateTimeInterface;
use ZenoAuth\Module\OAuth\Domain\Entity\Token;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface TokenHasherInterface
{
    /**
     * Hash the given token.
     *
     * @param Token             $token
     * @param DateTimeInterface $expiresAt
     *
     * @return string
     */
    public function hash(Token $token, DateTimeInterface $expiresAt): string;

    /**
     * Verify given token.
     *
     * @param string $token
     *
     * @return bool
     */
    public function verify(string $token): bool;

    /**
     * Get identifier for load token.
     *
     * @param string $token
     *
     * @return array
     */
    public function getIdentifier(string $token): array;
}
