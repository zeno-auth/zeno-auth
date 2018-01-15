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
use Doctrine\DBAL\Types\ArrayType;
use Psr\Container\ContainerInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class JsonObjectType extends ArrayType
{
    const TYPE_NAME = 'json_object';

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {

        if (is_object($value)) {
            $value = [
                'type' => get_class($value),
                'data' => $this->getContainer($platform)->get('serializer')->normalize($value),
            ];
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

            if (array_key_exists('type', $value) && array_key_exists('data', $value)) {
                $value =$this->getContainer($platform)->get('serializer')->denormalize($value['data'], $value['type']);
            }
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param AbstractPlatform $platform
     *
     * @return ContainerInterface
     */
    private function getContainer(AbstractPlatform $platform): ContainerInterface
    {
        $listeners = $platform->getEventManager()->getListeners('getContainer');

        $listener = array_shift($listeners);

        return $listener->getContainer();
    }
}
