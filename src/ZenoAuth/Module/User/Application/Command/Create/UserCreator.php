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

namespace ZenoAuth\Module\User\Application\Command\Create;

use ZenoAuth\Module\User\Domain\Contract\Command\CreateUser;
use ZenoAuth\Module\User\Domain\Factory\UserFactoryInterface;
use ZenoAuth\Module\User\Domain\Repository\UserRepositoryInterface;
use ZenoAuth\Module\User\Domain\Service\UserCreatorInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class UserCreator implements UserCreatorInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * @var UserFactoryInterface
     */
    private $factory;

    /**
     * Constructor.
     *
     * @param UserRepositoryInterface $repository
     * @param UserFactoryInterface    $factory
     */
    public function __construct(UserRepositoryInterface $repository, UserFactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function create(CreateUser $message): void
    {
        $this->repository->save($this->factory->create($message));
    }
}
