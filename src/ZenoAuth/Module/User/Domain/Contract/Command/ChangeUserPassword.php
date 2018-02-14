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

namespace ZenoAuth\Module\User\Domain\Contract\Command;

use Borobudur\Component\Messaging\Message\Command;
use Borobudur\Component\Value\User\Password\Hasher\Bcrypt;
use Borobudur\Component\Value\User\Password\Password;
use ZenoAuth\Module\User\Domain\Entity\UserId;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ChangeUserPassword extends Command
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $currentPassword;

    /**
     * @var string
     */
    private $password;

    public function getUser(): UserId
    {
        return new UserId($this->user);
    }

    public function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    public function getPassword(): Password
    {
        return Password::create($this->password, Bcrypt::create());
    }
}
