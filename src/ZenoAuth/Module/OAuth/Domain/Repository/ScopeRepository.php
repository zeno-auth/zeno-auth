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

namespace ZenoAuth\Module\OAuth\Domain\Repository;

use Borobudur\Component\Ddd\Collection;
use Borobudur\Component\Ddd\CollectionInterface;
use Borobudur\Component\Ddd\Lock\LockingInterface;
use Borobudur\Component\Ddd\Model;
use Borobudur\Component\Identifier\Identifier;
use RuntimeException;
use ZenoAuth\Module\OAuth\Domain\Model\Scope;
use ZenoAuth\Module\OAuth\Domain\Model\Scopes;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * @var CollectionInterface
     */
    protected $scopes;

    /**
     * Constructor.
     *
     * @param array $scopes
     */
    public function __construct(array $scopes)
    {
        $this->scopes = new Collection(
            array_map(
                function (array $scope) {
                    return Scope::fromArray($scope);
                },
                $scopes
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findAllByScopes(string $scopes): CollectionInterface
    {
        $scopes = explode(Scopes::SCOPE_SEPARATION, $scopes);

        return $this->scopes->filter(
            function (Scope $scope) use ($scopes) {
                return in_array((string) $scope->getId(), $scopes, true);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function find(Identifier $id, LockingInterface $lockMode = null): ?Model
    {
        return $this->scopes->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?Model
    {
        throw new RuntimeException('Unsupported method call "findOneBy"');
    }

    /**
     * {@inheritdoc}
     */
    public function findAllBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): CollectionInterface
    {
        throw new RuntimeException('Unsupported method call "findAllBy"');
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): CollectionInterface
    {
        return $this->scopes;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Model $model): void
    {
        $index = $this->scopes->indexOf($model);

        if ($index > 0) {
            $this->scopes->set($index, $model);
        } else {
            $this->scopes->add($model);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(Model $model): void
    {
        $this->scopes->removeElement($model);
    }
}
