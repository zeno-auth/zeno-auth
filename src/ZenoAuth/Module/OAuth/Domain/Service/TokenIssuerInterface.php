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

use ZenoAuth\Module\OAuth\Component\Hash\TokenHasherInterface;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\AbstractCreateToken;
use ZenoAuth\Module\OAuth\Domain\Entity\Token;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface TokenIssuerInterface
{
    public function issue(AbstractCreateToken $message, TokenHasherInterface $tokenHasher): Token;

    public function supports(AbstractCreateToken $message): bool;
}
