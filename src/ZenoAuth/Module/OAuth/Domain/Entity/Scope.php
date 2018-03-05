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

namespace ZenoAuth\Module\OAuth\Domain\Entity;

use Borobudur\Component\Ddd\Model;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface Scope extends Model
{
    public function setLabel(string $label): void;

    public function getLabel(): string;

    public function setDescription(string $description = null): void;

    public function getDescription(): ?string;
}
