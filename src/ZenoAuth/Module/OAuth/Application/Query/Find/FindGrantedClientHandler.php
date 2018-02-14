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

namespace ZenoAuth\Module\OAuth\Application\Query\Find;

use React\Promise\Deferred;
use ZenoAuth\Module\OAuth\Domain\Contract\Query\FindGrantedClient;
use ZenoAuth\Module\OAuth\Domain\Repository\GrantedClientRepositoryInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class FindGrantedClientHandler
{
    /**
     * @var GrantedClientRepositoryInterface
     */
    private $grantedClientRepository;

    /**
     * Constructor.
     *
     * @param GrantedClientRepositoryInterface $grantedClientRepository
     */
    public function __construct(GrantedClientRepositoryInterface $grantedClientRepository)
    {
        $this->grantedClientRepository = $grantedClientRepository;
    }

    public function handle(FindGrantedClient $message, Deferred $deferred): void
    {
        $criteria = ['user' => $message->getUser(), 'client' => $message->getClient()];

        $deferred->resolve($this->grantedClientRepository->findOneBy($criteria));
    }
}
