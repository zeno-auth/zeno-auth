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

namespace ZenoAuth\Module\OAuth\Application\Command\Issue;

use ZenoAuth\Module\OAuth\Application\Query\ClientFinder;
use ZenoAuth\Module\OAuth\Domain\Factory\AccessTokenFactoryInterface;
use ZenoAuth\Module\OAuth\Domain\Factory\TokenFactoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\AccessTokenRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\TokenRepositoryInterface;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class AccessTokenIssuer extends AbstractTokenIssuer
{
    /**
     * @var AccessTokenRepositoryInterface
     */
    private $repository;

    /**
     * @var AccessTokenFactoryInterface
     */
    private $factory;

    /**
     * @var int
     */
    private $ttl;

    /**
     * Constructor.
     *
     * @param ClientFinder                   $clientFinder
     * @param UserFinderInterface            $userFinder
     * @param AccessTokenRepositoryInterface $repository
     * @param AccessTokenFactoryInterface    $factory
     * @param int                            $ttl
     */
    public function __construct(ClientFinder $clientFinder, UserFinderInterface $userFinder, AccessTokenRepositoryInterface $repository, AccessTokenFactoryInterface $factory, int $ttl)
    {
        parent::__construct($clientFinder, $userFinder);

        $this->repository = $repository;
        $this->factory = $factory;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($message): bool
    {
        return $message instanceof CreateAccessToken;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFactory(): TokenFactoryInterface
    {
        return $this->factory;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRepository(): TokenRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenTtl(): int
    {
        return $this->ttl;
    }
}
