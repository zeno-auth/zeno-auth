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

use ZenoAuth\Module\OAuth\Component\Credential\CredentialManagerInterface;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\IssueToken;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class IssueTokenHandler
{
    /**
     * @var CredentialManagerInterface
     */
    private $credentialManager;

    /**
     * Constructor.
     *
     * @param CredentialManagerInterface $credentialManager
     */
    public function __construct(CredentialManagerInterface $credentialManager)
    {
        $this->credentialManager = $credentialManager;
    }

    /**
     * Handle issue token.
     *
     * @param IssueToken $message
     */
    public function handle(IssueToken $message): void
    {
        $signature = $this->credentialManager->grant($message->getMessagePayload());

        $message->setMessageReturn($signature);
    }
}
