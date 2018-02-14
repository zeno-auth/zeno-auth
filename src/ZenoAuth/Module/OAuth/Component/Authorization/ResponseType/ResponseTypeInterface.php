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

namespace ZenoAuth\Module\OAuth\Component\Authorization\ResponseType;

use Borobudur\Component\Parameter\ParameterInterface;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\User\Domain\Entity\User;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface ResponseTypeInterface
{
    /**
     * Get response type name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Respond the request.
     *
     * @param Client             $client
     * @param User               $user
     * @param ParameterInterface $request
     *
     * @return array
     */
    public function respond(Client $client, User $user, ParameterInterface $request): array;
}
