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

namespace ZenoAuth\Module\User\Domain\Entity;

use Borobudur\Component\Ddd\Model;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface Role extends Model
{
    public function getName(): string;

    public function setName(string $name): void;
}
