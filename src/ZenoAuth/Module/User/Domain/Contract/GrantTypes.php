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

namespace ZenoAuth\Module\User\Domain\Contract;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class GrantTypes
{
    const GRANT_TYPE_AUTH_CODE = 'authorization_code';
    const GRANT_TYPE_IMPLICIT = 'token';
    const GRANT_TYPE_USER_CREDENTIALS = 'password';
    const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';
    const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';
}
