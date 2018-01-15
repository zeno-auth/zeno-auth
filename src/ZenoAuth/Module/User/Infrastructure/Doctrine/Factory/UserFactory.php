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

namespace ZenoAuth\Module\User\Infrastructure\Doctrine\Factory;

use Borobudur\Component\Ddd\ModelMapperTrait;
use ZenoAuth\Module\User\Domain\Contract\Command\CreateUser;
use ZenoAuth\Module\User\Domain\Entity\User;
use ZenoAuth\Module\User\Domain\Factory\UserFactoryInterface;
use ZenoAuth\Module\User\Infrastructure\Doctrine\Entity\User as UserDoctrine;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class UserFactory implements UserFactoryInterface
{
    use ModelMapperTrait;

    public function create(CreateUser $message): User
    {
        $user = $this->fill(new UserDoctrine(), $message);
        $this->setId($message->getId(), $user);

        return $user;
    }
}
