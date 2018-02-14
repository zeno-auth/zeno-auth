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

namespace ZenoAuth\Module\OAuth\Component\Authorization;

use Borobudur\Component\Parameter\ParameterInterface;
use ZenoAuth\Module\OAuth\Component\Authorization\ResponseType\ResponseTypeInterface;
use ZenoAuth\Module\User\Domain\Entity\User;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface AuthorizationInterface
{
    public function add(ResponseTypeInterface $responseType): void;

    public function has(string $responseType): bool;

    public function verify(ParameterInterface $request): void;

    public function authorize(User $user, ParameterInterface $request): Authorized;
}
