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

namespace ZenoAuth\Shared\Value;

use InvalidArgumentException;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class Uri
{
    /**
     * @var string
     */
    private $uri;

    /**
     * Constructor.
     *
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        if (false === filter_var($uri, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(sprintf('"%s" is not valid uri.', $uri));
        }

        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->uri;
    }
}
