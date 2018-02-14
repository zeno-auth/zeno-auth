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
use ZenoAuth\Module\OAuth\Domain\Contract\Command\CreateAuthorizationCode;
use ZenoAuth\Module\OAuth\Domain\Entity\AuthorizationCode;
use ZenoAuth\Module\OAuth\Domain\Factory\AuthorizationCodeFactoryInterface;
use ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Entity\AuthorizationCode as AuthorizationCodeDoctrine;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class AuthorizationCodeFactory implements AuthorizationCodeFactoryInterface
{
    use ModelMapperTrait;

    public function create(CreateAuthorizationCode $message): AuthorizationCode
    {
        $authorizationCode = $this->fill(new AuthorizationCodeDoctrine(), $message);
        $this->setId($message->getId(), $authorizationCode);

        return $authorizationCode;
    }
}
