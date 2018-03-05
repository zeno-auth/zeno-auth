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
use ZenoAuth\Module\OAuth\Domain\Repository\GrantedClientRepositoryInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class GrantedClientRepository extends AbstractRepository implements GrantedClientRepositoryInterface
{
}
