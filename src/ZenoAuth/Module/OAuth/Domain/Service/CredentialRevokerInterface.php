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

use ZenoAuth\Module\OAuth\Domain\Contract\Command\RevokeCredential;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface CredentialRevokerInterface
{
    public function revoke(RevokeCredential $message): void;
}
