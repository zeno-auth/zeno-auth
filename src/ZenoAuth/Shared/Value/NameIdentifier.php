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

use Borobudur\Component\Identifier\Identifier;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class NameIdentifier implements Identifier
{
    /**
     * @var string
     */
    private $name;

    /**
     * Constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = str_replace(' ', '-', strtolower(preg_replace('/\s+/', ' ', $name)));
    }

    /**
     * {@inheritdoc}
     */
    public function getScalarValue()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getScalarValue();
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Identifier $other): bool
    {
        return $other instanceof NameIdentifier && $this->getScalarValue() === $other->getScalarValue();
    }
}
