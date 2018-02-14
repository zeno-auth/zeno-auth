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

namespace ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Factory;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
use Borobudur\Component\Ddd\ModelMapperTrait;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\GrantClient;
use ZenoAuth\Module\OAuth\Domain\Entity\GrantedClient;
use ZenoAuth\Module\OAuth\Domain\Factory\GrantedClientFactoryInterface;
use ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Entity\GrantedClient as GrantedClientDoctrine;

final class GrantedClientFactory implements GrantedClientFactoryInterface
{
    use ModelMapperTrait;

    public function create(GrantClient $message): GrantedClient
    {
        $granted = $this->fill(new GrantedClientDoctrine(), $message);
        $this->setId($message->getId(), $granted);

        return $granted;
    }
}
