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

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class Uris
{
    /**
     * @var Uri[]
     */
    private $uris = [];

    /**
     * Constructor.
     *
     * @param Uri[] $uris
     */
    public function __construct(array $uris)
    {
        foreach ($uris as $uri) {
            $this->add($uri);
        }
    }

    public static function fromArray(array $uris): Uris
    {
        return new Uris(
            array_map(
                function (string $uri) {
                    return new Uri($uri);
                },
                $uris
            )
        );
    }

    public function add(Uri $uri): void
    {
        $this->uris[] = $uri;
    }

    public function matches(string $other): bool
    {
        foreach ($this->uris as $uri) {
            if (preg_match(sprintf('/^%s/', addcslashes((string) $uri, '/.:')), $other)) {
                return true;
            }
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return count($this->uris) <= 0;
    }

    public function first(): ?Uri
    {
        return count($this->uris) ? $this->uris[0] : null;
    }

    public function all(): array
    {
        return $this->uris;
    }
}
