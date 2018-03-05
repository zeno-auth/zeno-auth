<?php
/**
 * This file is part of the Zeno Auth package.
 *
 * (c) 2018 Borobudur <http://borobudur.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    Borobudur\Infrastructure\Symfony\Bundle\DoctrineBundle\BorobudurDoctrineBundle::class => ['all' => true],
    ZenoAuth\Module\User\Infrastructure\Symfony\Bundle\UserDoctrineBundle\ZenoAuthUserDoctrineBundle::class => ['all' => true],
    ZenoAuth\Module\OAuth\Infrastructure\Symfony\Bundle\OAuthDoctrineBundle\ZenoOAuthDoctrineBundle::class => ['all' => true],
];
