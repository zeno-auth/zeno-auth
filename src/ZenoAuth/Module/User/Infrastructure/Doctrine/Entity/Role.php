<?php
/**
 * This file is part of the zeno-auth package.
 *
 * (c) 2018 Borobudur <http://borobudur.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ZenoAuth\Module\User\Infrastructure\Doctrine\Entity;

use Borobudur\Infrastructure\Doctrine\Model;
use ZenoAuth\Module\User\Domain\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ZenoAuth\Module\User\Infrastructure\Doctrine\Repository\RoleRepository")
 * @ORM\Table(name="roles")
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class Role extends Model implements Entity\Role
{
    use Entity\RoleTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     *
     * @var Entity\RoleId
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @var string
     */
    protected $name;
}
