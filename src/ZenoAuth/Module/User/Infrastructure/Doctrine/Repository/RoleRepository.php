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

namespace ZenoAuth\Module\User\Infrastructure\Doctrine\Repository;

use Borobudur\Infrastructure\Doctrine\AbstractRepository;
use ZenoAuth\Module\User\Domain\Repository\RoleRepositoryInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class RoleRepository extends AbstractRepository implements RoleRepositoryInterface
{
}
