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
use ZenoAuth\Module\OAuth\Domain\Contract\Query\FindAllScopesByIdentifiers;
use ZenoAuth\Module\OAuth\Domain\Repository\ScopeRepositoryInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class FindAllScopesByIdentifiersHandler
{
    /**
     * @var ScopeRepositoryInterface
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param ScopeRepositoryInterface $repository
     */
    public function __construct(ScopeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(FindAllScopesByIdentifiers $query, Deferred $deferred): void
    {
        $deferred->resolve($this->repository->findAllByScopes($query->getScopes()));
    }
}
