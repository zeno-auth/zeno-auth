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

namespace ZenoAuth\Module\OAuth\Component\Authorization\ResponseType;

use Borobudur\Component\Parameter\ImmutableParameter;
use Borobudur\Component\Parameter\ParameterInterface;
use ZenoAuth\Module\OAuth\Component\Credential\CredentialManagerInterface;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\User\Domain\Contract\GrantTypes;
use ZenoAuth\Module\User\Domain\Entity\User;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class TokenResponseType implements ResponseTypeInterface
{
    const RESPONSE_TYPE_NAME = 'token';

    /**
     * @var CredentialManagerInterface
     */
    private $credentialManager;

    /**
     * Constructor.
     *
     * @param CredentialManagerInterface $credentialManager
     */
    public function __construct(CredentialManagerInterface $credentialManager)
    {
        $this->credentialManager = $credentialManager;
    }

    /**
     * {@inheritdoc}
     */
    public function respond(Client $client, User $user, ParameterInterface $request): array
    {
        $signature = $this->credentialManager->grant(
            new ImmutableParameter(
                array_merge(
                    $request->all(),
                    ['grant_type' => GrantTypes::GRANT_TYPE_IMPLICIT]
                )
            )
        );

        return array_merge(
            $signature->payload(),
            ['state' => $request->get('state')]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return TokenResponseType::RESPONSE_TYPE_NAME;
    }
}
