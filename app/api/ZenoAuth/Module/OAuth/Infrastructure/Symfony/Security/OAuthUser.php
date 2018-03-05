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

namespace ZenoAuth\Api\Module\OAuth\Infrastructure\Symfony\Security;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ZenoAuth\Module\OAuth\Domain\Model\Scopes;
use ZenoAuth\Web\Infrastructure\Symfony\Security\AuthenticatedUser;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class OAuthUser implements UserInterface, EquatableInterface
{
    private const SCOPE_MAPS = [
        'basic' => ['username', 'name', 'roles'],
        'email' => ['email'],
    ];

    /**
     * @var AuthenticatedUser
     */
    private $user;

    /**
     * @var Scopes
     */
    private $scopes;

    /**
     * Constructor.
     *
     * @param AuthenticatedUser $user
     * @param Scopes            $scopes
     */
    public function __construct(AuthenticatedUser $user, Scopes $scopes)
    {
        $this->user = $user;
        $this->scopes = $scopes;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        return $this->user->isEqualTo($user);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->user->getRoles();
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
        return $this->user->getSalt();
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->user->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->user->eraseCredentials();
    }

    public function toArray(): array
    {
        $serialized = $this->user->jsonSerialize();
        $data = ['id' => $serialized['id']];

        foreach (self::SCOPE_MAPS as $scope => $fields) {
            if ($this->scopes->has($scope)) {
                foreach ($fields as $field) {
                    $data[$field] = $serialized[$field];
                }
            }
        }

        return $data;
    }
}
