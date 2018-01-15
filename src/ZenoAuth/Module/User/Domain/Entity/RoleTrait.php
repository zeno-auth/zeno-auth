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

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
trait RoleTrait
{
    /**
     * @var string
     */
    protected $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
