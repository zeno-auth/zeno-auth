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
use ZenoAuth\Module\OAuth\Domain\Entity\TokenId;
use ZenoAuth\Module\OAuth\Domain\Model\Scopes;
use ZenoAuth\Module\User\Domain\Entity\UserId;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
abstract class AbstractCreateToken extends Command
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $client;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var array
     */
    protected $scopes;

    /**
     * @return TokenId
     */
    public function getId(): TokenId
    {
        if (null === $this->id) {
            $this->id = TokenId::generate();
        }

        return $this->id;
    }

    /**
     * @return ClientId
     */
    public function getClient(): ClientId
    {
        return new ClientId($this->client);
    }

    /**
     * @return UserId
     */
    public function getUser(): UserId
    {
        return new UserId($this->user);
    }

    /**
     * @return Scopes
     */
    public function getScopes(): ?Scopes
    {
        if (!empty($this->scopes) && is_array($this->scopes)) {
            return Scopes::fromArray($this->scopes);
        }

        return null;
    }
}
