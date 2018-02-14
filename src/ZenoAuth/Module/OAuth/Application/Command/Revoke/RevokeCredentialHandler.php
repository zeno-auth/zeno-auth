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

namespace ZenoAuth\Module\OAuth\Application\Command\Revoke;

use ZenoAuth\Module\OAuth\Domain\Contract\Command\RevokeCredential;
use ZenoAuth\Module\OAuth\Domain\Service\CredentialRevokerInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class RevokeCredentialHandler
{
    /**
     * @var CredentialRevokerInterface
     */
    private $revoker;

    /**
     * Constructor.
     *
     * @param CredentialRevokerInterface $revoker
     */
    public function __construct(CredentialRevokerInterface $revoker)
    {
        $this->revoker = $revoker;
    }

    public function handle(RevokeCredential $message): void
    {
        $this->revoker->revoke($message);
    }
}
