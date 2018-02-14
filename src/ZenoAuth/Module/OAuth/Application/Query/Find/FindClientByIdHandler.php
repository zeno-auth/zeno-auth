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
use ZenoAuth\Module\OAuth\Application\Query\ClientFinder;
use ZenoAuth\Module\OAuth\Domain\Contract\Query\FindClientById;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class FindClientByIdHandler
{
    /**
     * @var ClientFinder
     */
    private $clientFinder;

    /**
     * Constructor.
     *
     * @param ClientFinder $clientFinder
     */
    public function __construct(ClientFinder $clientFinder)
    {
        $this->clientFinder = $clientFinder;
    }

    public function handle(FindClientById $query, Deferred $deferred = null): void
    {
        $deferred->resolve($this->clientFinder->findOrFail($query->getId()));
    }
}
