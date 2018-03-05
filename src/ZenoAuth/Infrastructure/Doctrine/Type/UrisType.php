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

namespace ZenoAuth\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use ZenoAuth\Shared\Value\Uri;
use ZenoAuth\Shared\Value\Uris;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class UrisType extends JsonType
{
    const TYPE_NAME = 'uris';

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof Uris) {
            $value = array_map(
                function (Uri $uri) {
                    return (string) $uri;
                },
                $value->all()
            );
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null !== $value) {
            $value = parent::convertToPHPValue($value, $platform);

            return Uris::fromArray($value);
        }

        return new Uris([]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::TYPE_NAME;
    }
}
