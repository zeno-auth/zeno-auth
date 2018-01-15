<?php
/**
 * This file is part of the Borobudur package.
 *
 * (c) 2017 Borobudur <http://borobudur.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App;

use Borobudur\Infrastructure\Symfony\Http\Kernel\AbstractKernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class Kernel extends AbstractKernel
{
    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function getCacheDir(): string
    {
        return dirname(__DIR__) . '/var/cache/' . $this->environment;
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__) . '/var/log';
    }

    protected function bundles(): array
    {
        return [dirname(__DIR__) . '/config/bundles.php'];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = dirname(__DIR__) . '/config';
        $loader->load($confDir . '/packages/*' . self::CONFIG_EXTS, 'glob');
        if (is_dir($confDir . '/packages/' . $this->environment)) {
            $loader->load(
                $confDir . '/packages/' . $this->environment . '/**/*'
                . self::CONFIG_EXTS,
                'glob'
            );
        }

        $loader->load($confDir . '/services' . self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = dirname(__DIR__) . '/config';
        if (is_dir($confDir . '/routes/')) {
            $routes->import(
                $confDir . '/routes/*' . self::CONFIG_EXTS,
                '/',
                'glob'
            );
        }

        if (is_dir($confDir . '/routes/' . $this->environment)) {
            $routes->import(
                $confDir . '/routes/' . $this->environment . '/**/*'
                . self::CONFIG_EXTS,
                '/',
                'glob'
            );
        }
        $routes->import($confDir . '/routes' . self::CONFIG_EXTS, '/', 'glob');
    }
}
