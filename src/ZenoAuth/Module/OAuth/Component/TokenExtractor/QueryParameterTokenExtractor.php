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
final class QueryParameterTokenExtractor implements TokenExtractorInterface
{
    /**
     * @var string
     */
    private $parameterName;

    /**
     * Constructor.
     *
     * @param string $parameterName
     */
    public function __construct(string $parameterName)
    {
        $this->parameterName = $parameterName;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(RequestInterface $request): bool
    {
        return $request->hasQueryParam($this->parameterName);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(RequestInterface $request): string
    {
        return $request->getQueryParam($this->parameterName);
    }
}
