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

namespace ZenoAuth\Module\OAuth\Component\Authorization;

use Borobudur\Component\Parameter\ParameterInterface;
use ZenoAuth\Module\OAuth\Component\Authorization\ResponseType\ResponseTypeInterface;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\OAuth\Domain\Entity\ClientId;
use ZenoAuth\Module\OAuth\Domain\Model\ScopeTransformerTrait;
use ZenoAuth\Module\OAuth\Domain\Repository\ClientRepositoryInterface;
use ZenoAuth\Module\OAuth\Domain\Repository\ScopeRepositoryInterface;
use ZenoAuth\Module\OAuth\Exception\OAuthException;
use ZenoAuth\Module\User\Domain\Entity\User;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class Authorization implements AuthorizationInterface
{
    use ScopeTransformerTrait;

    /**
     * @var ResponseTypeInterface[]
     */
    private $responseTypes = [];

    /**
     * @var ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * @var ScopeRepositoryInterface
     */
    private $scopeRepository;

    /**
     * Constructor.
     *
     * @param ResponseTypeInterface[]          $responseTypes
     * @param ClientRepositoryInterface        $clientRepository
     * @param ScopeRepositoryInterface         $scopeRepository
     */
    public function __construct(array $responseTypes = [], ClientRepositoryInterface $clientRepository, ScopeRepositoryInterface $scopeRepository)
    {
        foreach ($responseTypes as $responseType) {
            $this->add($responseType);
        }

        $this->clientRepository = $clientRepository;
        $this->scopeRepository = $scopeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function add(ResponseTypeInterface $responseType): void
    {
        $this->responseTypes[$responseType->getName()] = $responseType;
    }

    /**
     * {@inheritdoc}
     */
    public function verify(ParameterInterface $request): void
    {
        if (false === $request->has('client_id')) {
            throw OAuthException::invalidRequest('client_id');
        }

        if (false === $request->has('response_type')) {
            throw OAuthException::invalidRequest('response_type');
        }

        if (false === $request->has('scopes')) {
            throw OAuthException::invalidRequest('scopes');
        }

        $client = $this->findClient($request);
        $redirectUri = $request->get('continue', (string) $client->getRedirectUris()->first());

        if (empty($redirectUri)) {
            throw OAuthException::invalidRequest('continue');
        }

        if (null !== $redirectUris = $client->getRedirectUris()) {
            if (!$redirectUris->isEmpty() && !$redirectUris->matches($redirectUri)) {
                throw OAuthException::invalidRedirectUri($redirectUri);
            }
        }

        $responseType = $request->get('response_type');

        if (!$this->has($responseType)) {
            throw OAuthException::invalidResponseType($responseType);
        }

        $this->getScopes($request->get('scopes'));
    }

    /**
     * {@inheritdoc}
     */
    public function authorize(User $user, ParameterInterface $request): Authorized
    {
        $client = $this->findClient($request);
        $redirectUri = $request->get('continue', (string) $client->getRedirectUris()->first());
        $responseType = $request->get('response_type');
        $params = $this->responseTypes[$responseType]->respond($client, $user, $request);

        return new Authorized($redirectUri, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $responseType): bool
    {
        return isset($this->responseTypes[$responseType]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getScopeRepository(): ScopeRepositoryInterface
    {
        return $this->scopeRepository;
    }

    /**
     * Find client.
     *
     * @param ParameterInterface $request
     *
     * @return Client
     * @throws OAuthException
     */
    private function findClient(ParameterInterface $request): Client
    {
        $id = new ClientId($request->get('client_id'));
        if (null === $client = $this->clientRepository->find($id)) {
            throw OAuthException::invalidClient();
        }

        return $client;
    }
}
