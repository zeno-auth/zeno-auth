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
use Doctrine\DBAL\Types\TextType;
use ZenoAuth\Shared\Value\Uri;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class UriType extends TextType
{
    const TYPE_NAME = 'uri';

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof Uri) {
            return (string) $value;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uri
    {
        if (null !== $value) {
            return new Uri($value);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::TYPE_NAME;
    }
}
