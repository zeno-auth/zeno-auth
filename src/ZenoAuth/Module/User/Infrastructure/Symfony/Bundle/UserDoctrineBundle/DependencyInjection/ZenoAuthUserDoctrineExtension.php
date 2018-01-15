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

namespace ZenoAuth\Module\User\Infrastructure\Symfony\Bundle\UserDoctrineBundle\DependencyInjection;

use Borobudur\Infrastructure\Symfony\DependencyInjection\AbstractExtension;
use Borobudur\Infrastructure\Symfony\Doctrine\DoctrineBundleInterface;
use Borobudur\Infrastructure\Symfony\Doctrine\EnableDoctrineInterface;
use Borobudur\Infrastructure\Symfony\Doctrine\EnableDoctrineTrait;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ZenoAuthUserDoctrineExtension extends AbstractExtension implements EnableDoctrineInterface
{
    use EnableDoctrineTrait;

    protected function getDriver(): string
    {
        return DoctrineBundleInterface::DRIVER_DOCTRINE_ORM;
    }
}
