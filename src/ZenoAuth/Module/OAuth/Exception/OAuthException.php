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

namespace ZenoAuth\Module\OAuth\Exception;

use Borobudur\Component\Exception\Exception;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class OAuthException extends Exception
{
    /**
     * Invalid request parameter.
     *
     * @param string $parameter
     *
     * @return OAuthException
     */
    public static function invalidRequest(string $parameter): OAuthException
    {
        return new OAuthException(
            'The request is missing a "%parameter%" parameter.', ['parameter' => $parameter], 400
        );
    }

    /**
     * Invalid client error.
     *
     * @return OAuthException
     */
    public static function invalidClient(): OAuthException
    {
        return new OAuthException('Client authentication failed.', null, 401);
    }

    /**
     * Invalid scope error.
     *
     * @param string $scope
     *
     * @return OAuthException
     */
    public static function invalidScope(string $scope): OAuthException
    {
        return new OAuthException(
            'The requested "%scope%" scope is invalid, unknown, or malformed.', ['scope' => $scope], 400
        );
    }

    /**
     * Invalid grant type.
     *
     * @param string $granType
     *
     * @return OAuthException
     */
    public static function invalidGrantType(string $granType): OAuthException
    {
        return new OAuthException(
            'This client is forbidden use grant "%grant%".', ['grant' => $granType], 403
        );
    }

    public static function invalidResponseType(string $responseType): OAuthException
    {
        return new OAuthException(
            'Unknown response type "%response%".', ['response' => $responseType], 400
        );
    }

    /**
     * Invalid user authentication.
     *
     * @return OAuthException
     */
    public static function invalidUser(): OAuthException
    {
        return new OAuthException('User authentication failed.', null, 401);
    }

    /**
     * Invalid token.
     *
     * @return OAuthException
     */
    public static function invalidToken(): OAuthException
    {
        return new OAuthException('The given token is invalid.', null, 401);
    }

    /**
     * Invalid redirect uri.
     *
     * @param string $redirectUri
     *
     * @return OAuthException
     */
    public static function invalidRedirectUri(string $redirectUri): OAuthException
    {
        return new OAuthException('Forbidden redirect uri "%uri%"', ['uri' => $redirectUri], 403);
    }

    /**
     * @return OAuthException
     */
    public static function invalidAuthCode(): OAuthException
    {
        return new OAuthException('The given auth code is invalid.', null, 401);
    }
}
