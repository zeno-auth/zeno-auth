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

namespace ZenoAuth\Module\OAuth\Application\Command\Create;

use ZenoAuth\Module\OAuth\Domain\Contract\Command\CreateClient;
use ZenoAuth\Module\OAuth\Domain\Service\ClientCreatorInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class CreateClientHandler
{
    /**
     * @var ClientCreatorInterface
     */
    private $creator;

    /**
     * Constructor.
     *
     * @param ClientCreatorInterface $creator
     */
    public function __construct(ClientCreatorInterface $creator)
    {
        $this->creator = $creator;
    }

    public function handle(CreateClient $message): void
    {
        $this->creator->create($message);
    }
}
