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

namespace ZenoAuth\Module\OAuth\Application\Query;

use Borobudur\Component\Ddd\Exception\ModelNotFoundException;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\OAuth\Domain\Entity\ClientId;
use ZenoAuth\Module\OAuth\Domain\Repository\ClientRepositoryInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ClientFinder
{
    /**
     * @var ClientRepositoryInterface
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param ClientRepositoryInterface $repository
     */
    public function __construct(ClientRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findOrFail(ClientId $id): Client
    {
        if (null !== $client = $this->repository->find($id)) {
            return $client;
        }

        throw new ModelNotFoundException(Client::class, [$id]);
    }
}
