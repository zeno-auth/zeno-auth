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

namespace ZenoAuth\Module\User\Infrastructure\Doctrine\Entity;

use Borobudur\Component\Value\User\Email;
use Borobudur\Component\Value\User\Password\Password;
use Borobudur\Component\Value\User\Username;
use Borobudur\Infrastructure\Doctrine\Model;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use ZenoAuth\Module\User\Domain\Entity;

/**
 * @ORM\Entity(repositoryClass="ZenoAuth\Module\User\Infrastructure\Doctrine\Repository\UserRepository")
 * @ORM\Table(name="users")
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class User extends Model implements Entity\User
{
    use Entity\UserTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     *
     * @var Model
     */
    protected $id;

    /**
     * @ORM\Column(type="username", unique=true)
     * @var Username
     */
    protected $username;

    /**
     * @ORM\Column(type="json_object", nullable=true)
     * @var Password
     */
    protected $password;

    /**
     * @ORM\Column(type="email", unique=true)
     * @var Email
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTimeInterface
     */
    protected $lastLoginAt;
}
