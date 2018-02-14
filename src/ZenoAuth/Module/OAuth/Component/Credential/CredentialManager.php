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

namespace ZenoAuth\Module\OAuth\Component\Credential;

use Borobudur\Component\Parameter\ParameterInterface;
use RuntimeException;
use ZenoAuth\Module\OAuth\Component\Credential\Grant\CredentialInterface;
use ZenoAuth\Module\OAuth\Exception\OAuthException;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class CredentialManager implements CredentialManagerInterface
{
    /**
     * @var CredentialInterface[]
     */
    private $grants = [];

    /**
     * Constructor.
     *
     * @param CredentialInterface[]            $grants
     */
    public function __construct(array $grants = [])
    {
        foreach ($grants as $grant) {
            $this->add($grant);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add(CredentialInterface $grant): void
    {
        if (isset($this->grants[$grant->getName()])) {
            throw new RuntimeException(sprintf('Grant with name "%s" already registered.', $grant->getName()));
        }

        $this->grants[$grant->getName()] = $grant;
    }

    /**
     * {@inheritdoc}
     */
    public function grant(ParameterInterface $request): Signature
    {
        if (null === $grant = $request->get('grant_type')) {
            throw new OAuthException('Please specify a grant type.');
        }

        if (false === $this->has($grant)) {
            throw new RuntimeException(sprintf('Grant with name "%s" not registered.', $grant));
        }

        return $this->grants[$grant]->grant($request);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $grant): bool
    {
        return isset($this->grants[$grant]);
    }
}
