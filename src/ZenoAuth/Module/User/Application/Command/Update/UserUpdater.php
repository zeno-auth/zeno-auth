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

namespace ZenoAuth\Module\User\Application\Command\Update;

use ZenoAuth\Module\User\Domain\Contract\Command\UpdateUser;
use ZenoAuth\Module\User\Domain\Repository\UserRepositoryInterface;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;
use ZenoAuth\Module\User\Domain\Service\UserUpdaterInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class UserUpdater implements UserUpdaterInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * @var UserFinderInterface
     */
    private $finder;

    /**
     * Constructor.
     *
     * @param UserRepositoryInterface $repository
     * @param UserFinderInterface     $finder
     */
    public function __construct(UserRepositoryInterface $repository, UserFinderInterface $finder)
    {
        $this->repository = $repository;
        $this->finder = $finder;
    }

    public function update(UpdateUser $message): void
    {
        $user = $this->finder->findOrFail($message->getId());
        $user->setEmail($message->getEmail());
        $user->setName($message->getName());

        $this->repository->save($user);
    }
}
