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

namespace ZenoAuth\Module\OAuth\Domain\Contract\Command;

use Borobudur\Component\Messaging\Message\Command;
use ZenoAuth\Module\OAuth\Domain\Entity\ClientId;
use ZenoAuth\Module\User\Domain\Entity\UserId;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class CreateClient extends Command
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $redirectUris;

    /**
     * @return ClientId
     */
    public function getId(): ClientId
    {
        if (null === $this->id) {
            $this->id = ClientId::generate();
        }

        return $this->id;
    }

    /**
     * @return UserId
     */
    public function getUser(): UserId
    {
        return new UserId($this->user);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getRedirectUris(): ?array
    {
        return $this->redirectUris;
    }
}
