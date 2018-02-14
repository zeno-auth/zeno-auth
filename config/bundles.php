<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Symfony\Bundle\WebServerBundle\WebServerBundle::class => ['dev' => true],
    ZenoAuth\Infrastructure\Symfony\Bundle\ZenoAuthBundle\ZenoAuthBundle::class => ['all' => true],
    ZenoAuth\Infrastructure\Symfony\Bundle\ZenoAuthDoctrineBundle\ZenoAuthDoctrineBundle::class => ['all' => true],
    ZenoAuth\Web\Infrastructure\Symfony\ZenoAuthWebBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    ZenoAuth\Api\Infrastructure\Symfony\ZenoAuthApiBundle::class => ['all' => true],
];
