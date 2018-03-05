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

namespace ZenoAuth\Module\OAuth\Domain\Contract\Command;

use Borobudur\Component\Messaging\Message\Command;
use ZenoAuth\Module\OAuth\Domain\Entity\ClientId;
use ZenoAuth\Module\OAuth\Domain\Entity\GrantedClientId;
use ZenoAuth\Module\OAuth\Domain\Model\Scopes;
use ZenoAuth\Module\User\Domain\Entity\UserId;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class GrantClient extends Command
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
    private $client;

    /**
     * @var string
     */
    private $scopes;

    public function getId(): GrantedClientId
    {
        if (null === $this->id) {
            $this->id = GrantedClientId::generate();
        }

        return $this->id;
    }

    public function getUser(): UserId
    {
        return new UserId($this->user);
    }

    public function getClient(): ClientId
    {
        return new ClientId($this->client);
    }

    public function getScopes(): Scopes
    {
        return Scopes::fromString($this->scopes);
    }
}
