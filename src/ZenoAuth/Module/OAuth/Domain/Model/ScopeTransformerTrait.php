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

use Borobudur\Component\Ddd\Exception\ModelNotFoundException;
use ZenoAuth\Module\OAuth\Domain\Repository\ScopeRepositoryInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
trait ScopeTransformerTrait
{
    /**
     * Transform string scope to entity scope.
     *
     * @param string $scopes
     *
     * @return array
     * @throws ModelNotFoundException
     */
    protected function getScopes(string $scopes): array
    {
        $scopes = Scopes::fromString($scopes);
        $transformed = [];

        foreach ($scopes->all() as $identifier) {
            if (null !== $scope = $this->getScopeRepository()->find($identifier)) {
                $transformed[] = (string) $scope->getId();

                continue;
            }

            throw new ModelNotFoundException(Scope::class, [$identifier]);
        }

        return $transformed;
    }

    abstract protected function getScopeRepository(): ScopeRepositoryInterface;
}
