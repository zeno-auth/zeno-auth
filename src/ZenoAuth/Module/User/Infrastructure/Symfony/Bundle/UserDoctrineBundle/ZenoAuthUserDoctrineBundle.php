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

namespace ZenoAuth\Module\User\Infrastructure\Symfony\Bundle\UserDoctrineBundle;

use Borobudur\Infrastructure\Symfony\Bundle\AbstractBundle;
use Borobudur\Infrastructure\Symfony\Doctrine\DoctrineBundleInterface;
use Borobudur\Infrastructure\Symfony\Doctrine\DoctrineRegistrarTrait;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ZenoAuthUserDoctrineBundle extends AbstractBundle implements DoctrineBundleInterface
{
    use DoctrineRegistrarTrait;

    protected function getModelNamespace(): string
    {
        return 'ZenoAuth\Module\User\Infrastructure\Doctrine\Entity';
    }
}
