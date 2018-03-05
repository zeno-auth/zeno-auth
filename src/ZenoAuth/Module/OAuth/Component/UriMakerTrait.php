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

namespace ZenoAuth\Module\OAuth\Component;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
trait UriMakerTrait
{
    /**
     * Uri maker.
     *
     * @param string $uri
     * @param array  $params
     * @param string $queryDelimiter
     *
     * @return string
     */
    protected function makeRedirectUri(string $uri, array $params = [], string $queryDelimiter = '?'): string
    {
        $uri .= (strstr($uri, $queryDelimiter) === false) ? $queryDelimiter : '&';

        return $uri . http_build_query($params);
    }
}
