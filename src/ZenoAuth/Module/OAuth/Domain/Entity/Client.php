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

use Borobudur\Component\Ddd\Model;
use ZenoAuth\Module\User\Domain\Entity\User;
use ZenoAuth\Shared\Value\Uris;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface Client extends Model
{
    public function setName(string $name): void;

    public function getName(): string;

    public function setSecret(string $secret): void;

    public function getSecret(): string;

    public function setUser(User $user): void;

    public function getUser(): User;

    public function setRedirectUris(?Uris $redirectUris): void;

    public function getRedirectUris(): ?Uris;

    public function setAllowedGrantTypes(array $allowedGrantTypes): void;

    public function getAllowedGrantTypes(): array;

    public function setTrusted(bool $trusted): void;

    public function isTrusted(): bool;
}
