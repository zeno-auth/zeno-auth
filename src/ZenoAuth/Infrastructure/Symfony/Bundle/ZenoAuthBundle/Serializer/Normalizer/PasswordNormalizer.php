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

namespace ZenoAuth\Infrastructure\Symfony\Bundle\ZenoAuthBundle\Serializer\Normalizer;

use Borobudur\Component\Value\User\Password\Password;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class PasswordNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (null !== $data) {
            if (is_array($data)) {
                return Password::fromArray($data);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $this->supports($type);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): ?array
    {
        if ($object instanceof Password) {
            return $object->toArray();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return is_object($data) && $this->supports(get_class($data));
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    private function supports(string $class): bool
    {
        return $class === Password::class;
    }
}
