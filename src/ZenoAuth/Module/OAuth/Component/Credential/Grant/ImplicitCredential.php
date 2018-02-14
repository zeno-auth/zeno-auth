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

namespace ZenoAuth\Module\OAuth\Component\Credential\Grant;

use Borobudur\Component\Parameter\ParameterInterface;
use ZenoAuth\Module\OAuth\Component\Credential\Signature;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\User\Domain\Contract\GrantTypes;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ImplicitCredential extends AbstractCredential
{
    /**
     * @var bool
     */
    protected $needClientSecret = false;

    /**
     * {@inheritdoc}
     */
    protected function sign(Client $client, ParameterInterface $request): Signature
    {
        $scopes = $request->get('scopes', 'basic');
        $accessToken = $this->issueAccessToken($client, $client->getUser(), $scopes);

        return new Signature(
            $accessToken, null, [
                'token_type' => 'Bearer',
                'expires_in' => $accessToken->getTtl(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return GrantTypes::GRANT_TYPE_IMPLICIT;
    }
}
