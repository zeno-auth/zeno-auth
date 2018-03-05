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

namespace ZenoAuth\Module\OAuth\Infrastructure\Symfony\Bundle\OAuthDoctrineBundle;

use Borobudur\Infrastructure\Symfony\Bundle\AbstractBundle;
use Borobudur\Infrastructure\Symfony\Doctrine\DoctrineBundleInterface;
use Borobudur\Infrastructure\Symfony\Doctrine\DoctrineRegistrarTrait;
use Doctrine\DBAL\Types\Type;
use ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Type\ScopesType;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ZenoOAuthDoctrineBundle extends AbstractBundle implements DoctrineBundleInterface
{
    use DoctrineRegistrarTrait;

    private const DOCTRINE_TYPES = [
        ScopesType::class,
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

    protected function getModelNamespace(): string
    {
        return 'ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Entity';
    }
}
