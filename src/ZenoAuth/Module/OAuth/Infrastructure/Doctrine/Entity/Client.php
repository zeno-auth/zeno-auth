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

namespace ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Entity;

use Borobudur\Infrastructure\Doctrine\Model;
use ZenoAuth\Module\OAuth\Domain\Entity;
use Doctrine\ORM\Mapping as ORM;
use ZenoAuth\Module\User\Domain\Contract\GrantTypes;
use ZenoAuth\Module\User\Domain\Entity\User;
use ZenoAuth\Shared\Util\Random;
use ZenoAuth\Shared\Value\Uris;

/**
 * @ORM\Entity(repositoryClass="ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Repository\ClientRepository")
 * @ORM\Table(name="clients", indexes={@ORM\Index(columns={"secret"})})
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class Client extends Model implements Entity\Client
{
    use Entity\ClientTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     *
     * @var Entity\ClientId
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=150)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=50, options={"fixed" = true})
     *
     * @var string
     */
    protected $secret;

    /**
     * @ORM\ManyToOne(targetEntity="ZenoAuth\Module\User\Infrastructure\Doctrine\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="uris", nullable=true)
     *
     * @var Uris
     */
    protected $redirectUris;

    /**
     * @ORM\Column(type="json_object")
     *
     * @var array
     */
    protected $allowedGrantTypes = [];

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     *
     * @var bool
     */
    protected $trusted = false;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->allowedGrantTypes[] = GrantTypes::GRANT_TYPE_AUTH_CODE;

        $this->setSecret(Random::generateToken());
    }
}
