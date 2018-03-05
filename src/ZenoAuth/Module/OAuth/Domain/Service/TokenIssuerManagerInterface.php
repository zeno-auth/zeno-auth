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

namespace ZenoAuth\Module\OAuth\Domain\Service;

use ZenoAuth\Module\OAuth\Domain\Contract\Command\AbstractCreateToken;
use ZenoAuth\Module\OAuth\Domain\Entity\AccessToken;
use ZenoAuth\Module\OAuth\Domain\Entity\RefreshToken;
use ZenoAuth\Module\OAuth\Domain\Entity\Token;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface TokenIssuerManagerInterface
{
    /**
     * Add token issuer.
     *
     * @param TokenIssuerInterface $issuer
     */
    public function add(TokenIssuerInterface $issuer): void;

    /**
     * Issue a token with given message.
     *
     * @param AbstractCreateToken $message
     *
     * @return Token|AccessToken|RefreshToken
     */
    public function issue(AbstractCreateToken $message): Token;

    /**
     * Check support for issue token with given message.
     *
     * @param mixed $message
     *
     * @return bool
     */
    public function supports($message): bool;

    /**
     * Verify the given token is valid.
     *
     * @param string $token
     *
     * @return bool
     */
    public function verify(string $token): bool;

    /**
     * Get token identifier.
     *
     * @param string $token
     *
     * @return array
     */
    public function identify(string $token): array;
}
