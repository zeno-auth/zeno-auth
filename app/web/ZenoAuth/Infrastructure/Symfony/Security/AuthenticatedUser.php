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

namespace ZenoAuth\Web\Infrastructure\Symfony\Security;

use JsonSerializable;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ZenoAuth\Module\User\Domain\Entity\Role;
use ZenoAuth\Module\User\Domain\Entity\User;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class AuthenticatedUser implements UserInterface, JsonSerializable, EquatableInterface
{
    /**
     * @var User
     */
    private $user;

    /**
     * Constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        if (null !== $roles = $this->user->getRoles()) {
            $roles = $roles->map(
                function (Role $role) {
                    return 'ROLE_' . str_replace(' ', '_', strtoupper($role->getName()));
                }
            );
        }

        return array_merge(['ROLE_USER'], $roles->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->user->getPassword();
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return (string) $this->user->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        // Left blank intentionally
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user): bool
    {
        return $this->getUsername() === $user->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'id'       => (string) $this->user->getId(),
            'username' => $this->getUsername(),
            'roles'    => $this->getRoles(),
        ];
    }
}
