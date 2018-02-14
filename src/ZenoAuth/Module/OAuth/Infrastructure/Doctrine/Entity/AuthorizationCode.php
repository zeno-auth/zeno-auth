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
use ZenoAuth\Shared\Value\Uri;

/**
 * @ORM\Entity(repositoryClass="ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Repository\AuthorizationCodeRepository")
 * @ORM\Table(name="authorization_codes")
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class AuthorizationCode extends AbstractToken implements Entity\AuthorizationCode
{
    use Entity\TokenTrait, Entity\AuthorizationCodeTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     *
     * @var Entity\AuthorizationCodeId
     */
    protected $id;

    /**
     * @ORM\Column(type="uri")
     *
     * @var Uri
     */
    protected $redirectUri;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $state;
}
