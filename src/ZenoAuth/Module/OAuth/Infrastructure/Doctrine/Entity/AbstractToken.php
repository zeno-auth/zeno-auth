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
use DateTimeInterface;
use ZenoAuth\Module\OAuth\Domain\Entity\Token;
use Doctrine\ORM\Mapping as ORM;
use ZenoAuth\Module\OAuth\Domain\Model\Scopes;
use ZenoAuth\Module\User\Domain\Entity\User;
use ZenoAuth\Module\OAuth\Domain\Entity;

/**
 * @ORM\MappedSuperclass()
 * @ORM\Table(indexes={@ORM\Index(columns={"expires_at"})})
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
abstract class AbstractToken extends Model implements Token
{
    use Entity\TokenTrait;

    /**
     * @ORM\ManyToOne(targetEntity="ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Entity\Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     *
     * @var Client
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="ZenoAuth\Module\User\Infrastructure\Doctrine\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     *
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    protected $token;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    protected $expiresAt;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $ttl;

    /**
     * @ORM\Column(type="scopes")
     *
     * @var Scopes
     */
    protected $scopes;
}
