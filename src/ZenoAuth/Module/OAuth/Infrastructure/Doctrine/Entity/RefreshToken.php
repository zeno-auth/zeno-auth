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

use Doctrine\ORM\Mapping as ORM;
use ZenoAuth\Module\OAuth\Domain\Entity;
use ZenoAuth\Module\OAuth\Domain\Entity\AccessToken;

/**
 * @ORM\Entity(repositoryClass="ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Repository\RefreshTokenRepository")
 * @ORM\Table(name="refresh_tokens")
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class RefreshToken extends AbstractToken implements Entity\RefreshToken
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     *
     * @var Entity\TokenId
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Entity\AccessToken")
     * @ORM\JoinColumn(name="access_token_id", referencedColumnName="id")
     *
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * {@inheritdoc}
     */
    public function setAccessToken(?AccessToken $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }
}
