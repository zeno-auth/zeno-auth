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

use ZenoAuth\Module\OAuth\Application\Query\ClientFinder;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\GrantClient;
use ZenoAuth\Module\OAuth\Domain\Factory\GrantedClientFactoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\GrantedClientRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\GrantedClientCreatorInterface;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class GrantedClientCreator implements GrantedClientCreatorInterface
{
    /**
     * @var GrantedClientFactoryInterface
     */
    private $factory;

    /**
     * @var GrantedClientRepositoryInterface
     */
    private $repository;

    /**
     * @var ClientFinder
     */
    private $clientFinder;

    /**
     * @var UserFinderInterface
     */
    private $userFinder;

    /**
     * Constructor.
     *
     * @param GrantedClientFactoryInterface    $factory
     * @param GrantedClientRepositoryInterface $repository
     * @param ClientFinder                     $clientFinder
     * @param UserFinderInterface              $userFinder
     */
    public function __construct(GrantedClientFactoryInterface $factory, GrantedClientRepositoryInterface $repository, ClientFinder $clientFinder, UserFinderInterface $userFinder)
    {
        $this->factory = $factory;
        $this->repository = $repository;
        $this->clientFinder = $clientFinder;
        $this->userFinder = $userFinder;
    }

    public function create(GrantClient $message): void
    {
        $granted = $this->factory->create($message);
        $granted->setUser($this->userFinder->findOrFail($message->getUser()));
        $granted->setClient($this->clientFinder->findOrFail($message->getClient()));

        $this->repository->save($granted);
    }
}
