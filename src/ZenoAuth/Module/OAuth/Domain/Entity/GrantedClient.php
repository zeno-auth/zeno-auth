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
use ZenoAuth\Module\OAuth\Domain\Model\Scopes;
use ZenoAuth\Module\User\Domain\Entity\User;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface GrantedClient extends Model
{
    public function setUser(User $user): void;

    public function getUser(): User;

    public function setClient(Client $client): void;

    public function getClient(): Client;

    public function setScopes(Scopes $scopes): void;

    public function getScopes(): Scopes;
}
