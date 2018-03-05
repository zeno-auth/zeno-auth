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

namespace ZenoAuth\Module\OAuth\Component\Hash;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Lcobucci\Jose\Parsing\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\Builder;
use ZenoAuth\Module\OAuth\Exception\TokenException;
use ZenoAuth\Module\OAuth\Domain\Entity;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class JwtTokenHasher implements TokenHasherInterface
{
    /**
     * @var string
     */
    private $issuer;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var Rsa
     */
    private $signer;

    /**
     * Constructor.
     *
     * @param string $privateKey
     * @param string $publicKey
     * @param Rsa    $signer
     * @param string $phrase
     * @param string $issuer
     */
    public function __construct(string $privateKey, string $publicKey, Rsa $signer, string $phrase = '', string $issuer = null)
    {
        $this->issuer = $issuer;
        $this->privateKey = new Key(sprintf('file://%s', $privateKey), $phrase);
        $this->publicKey = new Key(sprintf('file://%s', $publicKey));
        $this->signer = $signer;
    }

    /**
     * {@inheritdoc}
     */
    public function hash(Entity\Token $token, DateTimeInterface $expiresAt): string
    {
        $issueAt = new DateTimeImmutable();
        $tokenBuilder = new Builder(new Parser());

        if (null !== $this->issuer) {
            $tokenBuilder->issuedBy($this->issuer);
        }

        $tokenBuilder->identifiedBy((string) $token->getId());
        $tokenBuilder->relatedTo((string) $token->getUser()->getId());
        $tokenBuilder->permittedFor((string) $token->getClient()->getId());
        $tokenBuilder->withClaim('scopes', (string) $token->getScopes());

        $tokenBuilder->issuedAt($issueAt);
        $tokenBuilder->canOnlyBeUsedAfter($issueAt);
        $tokenBuilder->expiresAt((new DateTimeImmutable())->setTimestamp($expiresAt->getTimestamp()));

        return (string) $tokenBuilder->getToken($this->signer, $this->privateKey);
    }

    /**
     * {@inheritdoc}
     */
    public function verify(string $token): bool
    {
        /** @var Token\Plain $token */
        $token = $this->parse($token);

        if ($this->signer->verify($token->signature()->hash(), $token->payload(), $this->publicKey)) {
            if ($token->isExpired(new DateTime())) {
                throw TokenException::expired();
            }

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(string $token): array
    {
        if (true === $this->verify($token)) {
            /** @var Token\Plain $token */
            $token = $this->parse($token);

            return ['id' => $token->claims()->get(Token\RegisteredClaims::ID)];
        }

        throw TokenException::invalidToken();
    }

    /**
     * Parse string token to token object.
     *
     * @param string $token
     *
     * @return Token
     */
    private function parse(string $token): Token
    {
        $parser = new Token\Parser(new Parser());

        return $parser->parse($token);
    }
}
