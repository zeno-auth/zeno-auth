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

namespace ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Entity;

use Borobudur\Infrastructure\Doctrine\Model;
use Doctrine\ORM\Mapping as ORM;
use ZenoAuth\Module\OAuth\Domain\Entity;
use ZenoAuth\Module\OAuth\Domain\Model\Scopes;
use ZenoAuth\Module\User\Domain\Entity\User;

/**
 * @ORM\Entity(repositoryClass="ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Repository\GrantedClientRepository")
 * @ORM\Table(name="granted_clients")
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class GrantedClient extends Model implements Entity\GrantedClient
{
    use Entity\GrantedClientTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     *
     * @var Entity\GrantedClientId
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="ZenoAuth\Module\User\Infrastructure\Doctrine\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var User
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Entity\Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var Entity\Client
     */
    protected $client;

    /**
     * @ORM\Column(type="scopes")
     *
     * @var Scopes
     */
    protected $scopes;
}
