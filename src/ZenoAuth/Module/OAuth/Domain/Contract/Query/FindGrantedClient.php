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

namespace ZenoAuth\Module\OAuth\Domain\Contract\Query;

use Borobudur\Component\Messaging\Message\Query;
use ZenoAuth\Module\OAuth\Domain\Entity\ClientId;
use ZenoAuth\Module\User\Domain\Entity\UserId;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class FindGrantedClient extends Query
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $client;

    public function getUser(): UserId
    {
        return new UserId($this->user);
    }

    public function getClient(): ClientId
    {
        return new ClientId($this->client);
    }
}
