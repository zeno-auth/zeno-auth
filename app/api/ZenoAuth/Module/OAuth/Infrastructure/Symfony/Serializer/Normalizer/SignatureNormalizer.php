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

namespace ZenoAuth\Api\Module\OAuth\Infrastructure\Symfony\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use ZenoAuth\Module\OAuth\Component\Credential\Signature;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class SignatureNormalizer implements NormalizerInterface
{
    /**
     * @param Signature $object
     *
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        return $object->payload();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Signature;
    }
}
