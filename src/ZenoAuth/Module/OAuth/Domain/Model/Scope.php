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

namespace ZenoAuth\Module\OAuth\Domain\Model;

use Borobudur\Component\Identifier\Identifier;
use ZenoAuth\Module\OAuth\Domain\Entity;
use ZenoAuth\Shared\Value\NameIdentifier;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class Scope implements Entity\Scope
{
    /**
     * @var NameIdentifier
     */
    protected $id;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $description;

    /**
     * Constructor.
     *
     * @param NameIdentifier $id
     * @param string         $label
     * @param string|null    $description
     */
    public function __construct(NameIdentifier $id, string $label, string $description = null)
    {
        $this->id = $id;
        $this->label = $label;
        $this->description = $description;
    }

    /**
     * @param array $data
     *
     * @return Entity\Scope
     */
    public static function fromArray(array $data): Entity\Scope
    {
        return new Scope(
            new NameIdentifier($data['id']),
            $data['label'],
            isset($data['description']) ? $data['description'] : null
        );
    }

    /**
     * Gets the model key.
     *
     * @return Identifier
     */
    public function getId(): Identifier
    {
        return $this->id;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setDescription(string $description = null): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
