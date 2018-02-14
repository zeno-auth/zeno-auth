<?php
/**
 * This file is part of the zeno-auth package.
 *
 * (c) 2018 Borobudur <http://borobudur.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ZenoAuth\Module\OAuth\Domain\Entity;

use ZenoAuth\Module\User\Domain\Entity\User;
use ZenoAuth\Shared\Value\Uris;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
trait ClientTrait
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Uris
     */
    protected $redirectUris;

    /**
     * @var array
     */
    protected $allowedGrantTypes = [];

    /**
     * @var bool
     */
    protected $trusted = false;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Uris
     */
    public function getRedirectUris(): ?Uris
    {
        return $this->redirectUris;
    }

    /**
     * @param Uris $redirectUris
     */
    public function setRedirectUris(?Uris $redirectUris): void
    {
        $this->redirectUris = $redirectUris;
    }

    /**
     * @return array
     */
    public function getAllowedGrantTypes(): array
    {
        return $this->allowedGrantTypes;
    }

    /**
     * @param array $allowedGrantTypes
     */
    public function setAllowedGrantTypes(array $allowedGrantTypes): void
    {
        $this->allowedGrantTypes = $allowedGrantTypes;
    }

    /**
     * @return bool
     */
    public function isTrusted(): bool
    {
        return $this->trusted;
    }

    /**
     * @param bool $trusted
     */
    public function setTrusted(bool $trusted): void
    {
        $this->trusted = $trusted;
    }
}
