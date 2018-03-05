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
use Borobudur\Component\Value\User\Email;
use Borobudur\Component\Value\User\Password\Hasher\Bcrypt;
use Borobudur\Component\Value\User\Password\Password;
use Borobudur\Component\Value\User\Username;
use ZenoAuth\Module\User\Domain\Entity\UserId;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class CreateUser extends Command
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    public function getId(): UserId
    {
        if (null === $this->id) {
            $this->id = UserId::generate();
        }

        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): Username
    {
        return new Username($this->username);
    }

    public function getPassword(): Password
    {
        return Password::create($this->password, Bcrypt::create());
    }

    public function getEmail(): Email
    {
        return new Email($this->email);
    }
}
