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

namespace ZenoAuth\Module\User\Application\Query;

use Borobudur\Component\Ddd\Exception\ModelNotFoundException;
use ZenoAuth\Module\User\Domain\Entity\User;
use ZenoAuth\Module\User\Domain\Entity\UserId;
use ZenoAuth\Module\User\Domain\Repository\UserRepositoryInterface;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class UserFinder implements UserFinderInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findOrFail(UserId $id): User
    {
        if (null !== $user = $this->repository->find($id)) {
            return $user;
        }

        throw new ModelNotFoundException(User::class, [$id]);
    }
}
