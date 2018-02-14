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

namespace ZenoAuth\Module\OAuth\Component\Authorization\Hash;

use DateTime;
use DateTimeInterface;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use ZenoAuth\Module\OAuth\Domain\Entity\Token;
use ZenoAuth\Module\OAuth\Exception\OAuthException;
use ZenoAuth\Module\OAuth\Exception\TokenException;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class CodeHasher implements CodeHasherInterface
{
    /**
     * @var string
     */
    private $secret;

    /**
     * Constructor.
     *
     * @param string $secret
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * {@inheritdoc}
     */
    public function hash(Token $token, DateTimeInterface $expiresAt): string
    {
        $payload = [
            'id'         => (string) $token->getId(),
            'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
        ];

        return $this->encrypt($payload);
    }

    /**
     * {@inheritdoc}
     */
    public function verify(string $token): bool
    {
        try {
            $payload = $this->decrypt($token);

            return isset($payload['id']);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(string $token): array
    {
        $payload = $this->decrypt($token);

        return ['id' => $payload['id']];
    }

    /**
     * Encrypt payload to token.
     *
     * @param array $payload
     *
     * @return string
     * @throws EnvironmentIsBrokenException
     */
    private function encrypt(array $payload): string
    {
        return Crypto::encryptWithPassword(json_encode($payload), $this->secret);
    }

    /**
     * Decrypt token to payload.
     *
     * @param string $token
     *
     * @return array
     * @throws OAuthException
     */
    private function decrypt(string $token): array
    {
        try {
            $payload = json_decode(Crypto::decryptWithPassword($token, $this->secret), true);

            if (isset($payload['expires_at']) && new DateTime() > new DateTime($payload['expires_at'])) {
                throw TokenException::expired();
            }

            return $payload;
        } catch (\Throwable $e) {
            throw OAuthException::invalidToken();
        }
    }
}
