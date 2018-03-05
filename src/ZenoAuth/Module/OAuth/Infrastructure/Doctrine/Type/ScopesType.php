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

namespace ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;
use ZenoAuth\Module\OAuth\Domain\Model\Scopes;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class ScopesType extends TextType
{
    const TYPE_NAME = 'scopes';

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof Scopes) {
            return (string) $value;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Scopes
    {
        if (null !== $value) {
            return Scopes::fromString($value);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::TYPE_NAME;
    }
}
