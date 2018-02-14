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

namespace ZenoAuth\Module\OAuth\Domain\Factory;

use ZenoAuth\Module\OAuth\Domain\Contract\Command\CreateClient;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface ClientFactoryInterface
{
    public function create(CreateClient $message): Client;
}
