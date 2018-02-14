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

namespace ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Factory;

use Borobudur\Component\Ddd\ModelMapperTrait;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\CreateClient;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\OAuth\Domain\Factory\ClientFactoryInterface;
use ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Entity\Client as ClientDoctrine;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ClientFactory implements ClientFactoryInterface
{
    use ModelMapperTrait;

    public function create(CreateClient $message): Client
    {
        $client = $this->fill(new ClientDoctrine(), $message);
        $this->setId($message->getId(), $client);

        return $client;
    }
}
