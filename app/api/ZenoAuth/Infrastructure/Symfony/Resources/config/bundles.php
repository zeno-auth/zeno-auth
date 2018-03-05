<?php

return [
    'before' => [
        FOS\RestBundle\FOSRestBundle::class => ['all' => true],
        Borobudur\Infrastructure\Symfony\Bundle\ApiBundle\BorobudurApiBundle::class => ['all' => true],
    ],
    ZenoAuth\Api\Module\OAuth\Infrastructure\Symfony\ZenoOAuthApiBundle::class => ['all' => true],
];
