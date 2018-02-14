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

namespace ZenoAuth\Module\OAuth\Domain\Contract\Query;

use Borobudur\Component\Messaging\Message\Query;
use ZenoAuth\Module\OAuth\Domain\Entity\ClientId;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class FindClientById extends Query
{
    /**
     * @var string
     */
    private $id;

    /**
     * @return ClientId
     */
    public function getId(): ClientId
    {
        return new ClientId($this->id);
    }
}
