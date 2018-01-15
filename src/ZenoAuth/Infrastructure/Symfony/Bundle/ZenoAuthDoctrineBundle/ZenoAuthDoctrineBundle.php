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

namespace ZenoAuth\Infrastructure\Symfony\Bundle\ZenoAuthDoctrineBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use ZenoAuth\Infrastructure\Doctrine\Type\EmailType;
use ZenoAuth\Infrastructure\Doctrine\Type\JsonObjectType;
use ZenoAuth\Infrastructure\Doctrine\Type\UsernameType;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ZenoAuthDoctrineBundle extends Bundle
{
    private const DOCTRINE_TYPES = [
        UsernameType::class,
        EmailType::class,
        JsonObjectType::class,
    ];

    public function boot(): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $conn = $em->getConnection();
        $platform = $conn->getDatabasePlatform();

        foreach (self::DOCTRINE_TYPES as $type) {
            $name = constant(sprintf('%s::TYPE_NAME', $type));

            if (Type::hasType($name)) {
                Type::overrideType($name, $type);
            } else {
                Type::addType($name, $type);
            }

            $platform->registerDoctrineTypeMapping($name, $name);
        }
    }
}
