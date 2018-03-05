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

namespace ZenoAuth\Module\OAuth\Infrastructure\Doctrine\Repository;

use Borobudur\Infrastructure\Doctrine\AbstractRepository;
use ZenoAuth\Module\OAuth\Domain\Repository\TokenRepositoryInterface;
use ZenoAuth\Module\User\Domain\Entity\UserId;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
abstract class AbstractTokenRepository extends AbstractRepository implements TokenRepositoryInterface
{
    public function removeByUser(UserId $user): void
    {
        $qb = $this->createQueryBuilder('token');

        $qb->where($qb->expr()->eq('token.user', ':user'));

        $stmt = $this->_em->createQuery($qb->delete()->getDQL());
        $stmt->execute(['user' => $user]);
    }
}
