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

namespace ZenoAuth\Module\OAuth\Domain\Model;

use RuntimeException;
use ZenoAuth\Shared\Value\NameIdentifier;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class Scopes
{
    const SCOPE_SEPARATION = ' ';

    /**
     * @var NameIdentifier[]
     */
    private $scopes = [];

    /**
     * Constructor.
     *
     * @param NameIdentifier[] $scopes
     */
    public function __construct(array $scopes)
    {
        foreach ($scopes as $scope) {
            $this->add($scope);
        }
    }

    public static function fromString(string $scopes): Scopes
    {
        return Scopes::fromArray(explode(self::SCOPE_SEPARATION, $scopes));
    }

    public static function fromArray(array $scopes): Scopes
    {
        return new Scopes(
            array_filter(
                array_map(
                    function (string $scope) {
                        return new NameIdentifier($scope);
                    },
                    $scopes
                )
            )
        );
    }

    public function add(NameIdentifier $scope): void
    {
        if ($this->has((string) $scope)) {
            throw new RuntimeException(sprintf('Scope "%s" already registered.', $scope));
        }

        $this->scopes[(string) $scope] = $scope;
    }

    public function merge(Scopes $scopes): Scopes
    {
        $newScopes = [];
        foreach ($scopes->all() as $scope) {
            if ($this->has((string) $scope)) {
                continue;
            }

            $newScopes[] = $scope;
        }

        return new Scopes(array_merge($this->scopes, $newScopes));
    }

    public function has(string $scope): bool
    {
        return array_key_exists((string) $scope, $this->scopes);
    }

    public function hasAny(Scopes $scopes): bool
    {
        foreach ($this->scopes as $scope) {
            if (true === $scopes->has((string) $scope)) {
                return true;
            }
        }

        return false;
    }

    public function hasDiff(Scopes $scopes): bool
    {
        return 0 !== count($this->diff($scopes));
    }

    public function diff(Scopes $scopes): array
    {
        return array_diff($scopes->keys(), $this->keys());
    }

    public function keys(): array
    {
        return array_keys($this->scopes);
    }

    /**
     * @return NameIdentifier[]
     */
    public function all(): array
    {
        return array_values($this->scopes);
    }

    public function __toString(): string
    {
        return implode(self::SCOPE_SEPARATION, array_keys($this->scopes));
    }
}
