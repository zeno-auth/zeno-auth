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

use Borobudur\Component\Ddd\ModelMapperTrait;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\AbstractCreateToken;
use ZenoAuth\Module\OAuth\Domain\Entity\Token;
use ZenoAuth\Module\OAuth\Domain\Factory\TokenFactoryInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
abstract class AbstractTokenFactory implements TokenFactoryInterface
{
    use ModelMapperTrait;

    public function create(AbstractCreateToken $message): Token
    {
        $token = $this->fill($this->getEntity(), $message);
        $this->setId($message->getId(), $token);

        return $token;
    }

    abstract protected function getEntity(): Token;
}
