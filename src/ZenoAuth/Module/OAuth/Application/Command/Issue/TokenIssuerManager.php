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

use RuntimeException;
use ZenoAuth\Module\OAuth\Component\Hash\TokenHasherInterface;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\AbstractCreateToken;
use ZenoAuth\Module\OAuth\Domain\Entity\Token;
use ZenoAuth\Module\OAuth\Domain\Service\TokenIssuerInterface;
use ZenoAuth\Module\OAuth\Domain\Service\TokenIssuerManagerInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class TokenIssuerManager implements TokenIssuerManagerInterface
{
    /**
     * @var TokenIssuerInterface[]
     */
    private $issuers = [];

    /**
     * @var TokenHasherInterface
     */
    private $tokenHasher;

    /**
     * Constructor.
     *
     * @param TokenHasherInterface   $tokenHasher
     * @param TokenIssuerInterface[] $issuers
     */
    public function __construct(TokenHasherInterface $tokenHasher, array $issuers = [])
    {
        foreach ($issuers as $creator) {
            $this->add($creator);
        }

        $this->tokenHasher = $tokenHasher;
    }

    /**
     * {@inheritdoc}
     */
    public function add(TokenIssuerInterface $issuer): void
    {
        $this->issuers[] = $issuer;
    }

    /**
     * {@inheritdoc}
     */
    public function issue(AbstractCreateToken $message): Token
    {
        foreach ($this->issuers as $issuer) {
            if ($issuer->supports($message)) {
                return $issuer->issue($message, $this->tokenHasher);
            }
        }

        throw new RuntimeException(sprintf('Token creator for "%s" not found.', get_class($message)));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($message): bool
    {
        foreach ($this->issuers as $issuer) {
            if ($issuer->supports($message)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function verify(string $token): bool
    {
        return $this->tokenHasher->verify($token);
    }

    /**
     * {@inheritdoc}
     */
    public function identify(string $token): array
    {
        return $this->tokenHasher->getIdentifier($token);
    }
}
