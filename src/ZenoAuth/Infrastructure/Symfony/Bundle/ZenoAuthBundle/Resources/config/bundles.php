<?php

return [
    'before' => [
        Borobudur\Infrastructure\Symfony\Bundle\ProophMessagingBundle\BorobudurProophMessagingBundle::class => ['all' => true],
        ZenoAuth\Module\User\Infrastructure\Symfony\Bundle\UserBundle\ZenoAuthUserBundle::class => ['all' => true],
        ZenoAuth\Module\OAuth\Infrastructure\Symfony\Bundle\OAuthBundle\ZenoOAuthBundle::class => ['all' => true],
    ],
];
