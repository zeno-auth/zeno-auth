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

namespace ZenoAuth\Module\OAuth\Application\Command\Grant;

use Borobudur\Component\Ddd\Exception\ModelNotFoundException;
use ZenoAuth\Module\OAuth\Application\Query\ClientFinder;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\GrantClient;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\OAuth\Domain\Entity\GrantedClient;
use ZenoAuth\Module\OAuth\Domain\Model\Scopes;
use ZenoAuth\Module\OAuth\Domain\Repository\GrantedClientRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Service\GrantedClientCreatorInterface;
use ZenoAuth\Module\User\Domain\Entity\User;
use ZenoAuth\Module\User\Domain\Service\UserFinderInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class GrantClientHandler
{
    /**
     * @var GrantedClientCreatorInterface
     */
    private $creator;

    /**
     * @var GrantedClientRepositoryInterface
     */
    private $repository;

    /**
     * @var UserFinderInterface
     */
    private $userFinder;

    /**
     * @var ClientFinder
     */
    private $clientFinder;

    /**
     * Constructor.
     *
     * @param GrantedClientCreatorInterface    $creator
     * @param GrantedClientRepositoryInterface $repository
     * @param UserFinderInterface              $userFinder
     * @param ClientFinder                     $clientFinder
     */
    public function __construct(GrantedClientCreatorInterface $creator, GrantedClientRepositoryInterface $repository, UserFinderInterface $userFinder, ClientFinder $clientFinder)
    {
        $this->creator = $creator;
        $this->repository = $repository;
        $this->userFinder = $userFinder;
        $this->clientFinder = $clientFinder;
    }

    /**
     * @param GrantClient $message
     *
     * @throws ModelNotFoundException
     */
    public function handle(GrantClient $message): void
    {
        $client = $this->clientFinder->findOrFail($message->getClient());
        $user = $this->userFinder->findOrFail($message->getUser());

        $this->grant($client, $user, $message->getScopes());
    }

    /**
     * @param Client $client
     * @param User   $user
     * @param Scopes $scopes
     */
    private function grant(Client $client, User $user, Scopes $scopes): void
    {
        if (null !== $granted = $this->findGrantedClient($user, $client)) {
            if ($granted->getScopes()->hasDiff($scopes)) {
                $granted->setScopes($granted->getScopes()->merge($scopes));

                $this->repository->save($granted);
            }

            return;
        }

        $this->creator->create(
            new GrantClient(
                [
                    'client' => (string) $client->getId(),
                    'user'   => (string) $user->getId(),
                    'scopes' => (string) $scopes,
                ]
            )
        );
    }

    /**
     * @param User   $user
     * @param Client $client
     *
     * @return GrantedClient
     */
    private function findGrantedClient(User $user, Client $client): ?GrantedClient
    {
        return $this->repository->findOneBy(
            [
                'user'   => $user->getId(),
                'client' => $client->getId(),
            ]
        );
    }
}
