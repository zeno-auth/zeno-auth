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
use Generator;
use IteratorAggregate;
use RuntimeException;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ChainTokenExtractor implements TokenExtractorInterface, IteratorAggregate
{
    /**
     * @var TokenExtractorInterface[]
     */
    private $map;

    /**
     * Constructor.
     *
     * @param TokenExtractorInterface[] $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * @param TokenExtractorInterface $extractor
     */
    public function add(TokenExtractorInterface $extractor): void
    {
        $this->map[] = $extractor;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(RequestInterface $request): bool
    {
        foreach ($this->getIterator() as $extractor) {
            if (true === $extractor->supports($request)) {
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
        foreach ($this->getIterator() as $extractor) {
            if (true === $extractor->supports($request)) {
                return $extractor->extract($request);
            }
        }

        throw new RuntimeException('There are no extractor support for this request.');
    }

    /**
     * @return Generator|TokenExtractorInterface[]
     */
    public function getIterator(): Generator
    {
        foreach ($this->map as $extractor) {
            if ($extractor instanceof TokenExtractorInterface) {
                yield $extractor;
            }
        }
    }
}
