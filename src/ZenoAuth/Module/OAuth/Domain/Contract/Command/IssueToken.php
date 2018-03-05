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

namespace ZenoAuth\Module\OAuth\Domain\Contract\Command;

use Borobudur\Component\Messaging\Message\MessageInterface;
use Borobudur\Component\Messaging\Message\PayloadMessageInterface;
use Borobudur\Component\Messaging\Message\ReturnableMessageInterface;
use Borobudur\Component\Messaging\Message\ReturnableMessageTrait;
use Borobudur\Component\Parameter\ImmutableParameter;
use Borobudur\Component\Parameter\ParameterInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class IssueToken implements MessageInterface, PayloadMessageInterface, ReturnableMessageInterface
{
    use ReturnableMessageTrait;

    /**
     * @var ImmutableParameter
     */
    private $payload;

    /**
     * Constructor.
     *
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = new ImmutableParameter($payload);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageType(): string
    {
        return MessageInterface::TYPE_COMMAND;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessagePayload(): ?ParameterInterface
    {
        return $this->payload;
    }
}
