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

use Borobudur\Component\Ddd\CollectionInterface;
use Borobudur\Component\Ddd\Model;
use Borobudur\Component\Value\User\Email;
use Borobudur\Component\Value\User\Password\Password;
use Borobudur\Component\Value\User\Username;
use DateTimeInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface User extends Model
{
    public function getUsername(): Username;

    public function setUsername(Username $username): void;

    public function getPassword(): Password;

    public function setPassword(Password $password): void;

    public function getEmail(): Email;

    public function setEmail(Email $email): void;

    public function getLastLoginAt(): DateTimeInterface;

    public function setLastLoginAt(DateTimeInterface $lastLoginAt): void;

    /**
     * @return CollectionInterface|Role[]|null
     */
    public function getRoles(): ?CollectionInterface;

    public function setRoles(CollectionInterface $roles): void;
}
