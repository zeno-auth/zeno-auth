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

namespace ZenoAuth\Module\User\Infrastructure\Doctrine\Repository;

use Borobudur\Component\Value\User\Email;
use Borobudur\Component\Value\User\Username;
use Borobudur\Infrastructure\Doctrine\AbstractRepository;
use ZenoAuth\Module\User\Domain\Entity\User;
use ZenoAuth\Module\User\Domain\Repository\UserRepositoryInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function findUserByUsernameOrEmail(string $identifier): ?User
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return $this->findOneBy(['email' => new Email($identifier)]);
        }

        return $this->findOneBy(['username' => new Username($identifier)]);
    }
}
