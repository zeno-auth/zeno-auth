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

namespace ZenoAuth\Module\OAuth\Component\TokenExtractor;

use Borobudur\Component\Http\RequestInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class AuthorizationHeaderTokenExtractor implements TokenExtractorInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $prefix;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $prefix
     */
    public function __construct(string $name, ?string $prefix)
    {
        $this->name = $name;
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(RequestInterface $request): bool
    {
        if ($request->hasHeader($this->name)) {
            $authorizationHeader = $request->getHeader($this->name);
            $authorizationHeader = reset($authorizationHeader);

            if (null === $this->prefix) {
                return true;
            }

            $headerParts = explode(' ', $authorizationHeader);

            if (count($headerParts) === 2 && $headerParts[0] === $this->prefix) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function extract(RequestInterface $request): string
    {
        $authorizationHeader = $request->getHeader($this->name);
        $authorizationHeader = reset($authorizationHeader);

        if (null === $this->prefix) {
            return $authorizationHeader;
        }

        $headerParts = explode(' ', $authorizationHeader);

        return $headerParts[1];
    }
}
