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

namespace ZenoAuth\Module\User\Domain\Entity;

use Borobudur\Component\Ddd\Collection;
use Borobudur\Component\Ddd\CollectionInterface;
use Borobudur\Component\Value\User\Email;
use Borobudur\Component\Value\User\Password\Password;
use Borobudur\Component\Value\User\Username;
use DateTimeInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
trait UserTrait
{
    /**
     * @var Username
     */
    protected $username;

    /**
     * @var Password
     */
    protected $password;

    /**
     * @var Email
     */
    protected $email;

    /**
     * @var DateTimeInterface
     */
    protected $lastLoginAt;

    /**
     * @var CollectionInterface
     */
    protected $roles;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->roles = new Collection();
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function setUsername(Username $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function setPassword(Password $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function getLastLoginAt(): DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(DateTimeInterface $lastLoginAt): void
    {
        $this->lastLoginAt = $lastLoginAt;
    }

    public function getRoles(): ?CollectionInterface
    {
        return $this->roles;
    }

    public function setRoles(CollectionInterface $roles): void
    {
        $this->roles = $roles;
    }
}
