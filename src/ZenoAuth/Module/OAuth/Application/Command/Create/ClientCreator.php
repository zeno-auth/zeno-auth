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
use ZenoAuth\Module\OAuth\Domain\Factory\ClientFactoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\ClientRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\ClientCreatorInterface;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ClientCreator implements ClientCreatorInterface
{
    /**
     * @var ClientRepositoryInterface
     */
    private $repository;

    /**
     * @var ClientFactoryInterface
     */
    private $factory;

    /**
     * @var UserFinderInterface
     */
    private $userFinder;

    /**
     * Constructor.
     *
     * @param ClientRepositoryInterface $repository
     * @param ClientFactoryInterface    $factory
     * @param UserFinderInterface       $userFinder
     */
    public function __construct(ClientRepositoryInterface $repository, ClientFactoryInterface $factory, UserFinderInterface $userFinder)
    {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->userFinder = $userFinder;
    }

    public function create(CreateClient $message): void
    {
        $client = $this->factory->create($message);
        $client->setUser($this->userFinder->findOrFail($message->getUser()));

        $this->repository->save($client);
    }
}
