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

/**
 * @ORM\Entity(repositoryClass="ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Repository\AccessTokenRepository")
 * @ORM\Table(name="access_tokens")
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class AccessToken extends AbstractToken implements Entity\AccessToken
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     *
     * @var Entity\TokenId
     */
    protected $id;
}
