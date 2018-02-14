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

use InvalidArgumentException;
use ZenoAuth\Module\User\Domain\Contract\Command\ChangeUserPassword;
use ZenoAuth\Module\User\Domain\Repository\UserRepositoryInterface;
use ZenoAuth\Module\User\Domain\Service\UserPasswordUpdaterInterface;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class UserPasswordUpdater implements UserPasswordUpdaterInterface
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

    public function changePassword(ChangeUserPassword $message): void
    {
        $user = $this->finder->findOrFail($message->getUser());

        if (!$user->getPassword()->matches($message->getCurrentPassword())) {
            throw new InvalidArgumentException('The current password is not match.');
        }

        $user->setPassword($message->getPassword());

        $this->repository->save($user);
    }
}
