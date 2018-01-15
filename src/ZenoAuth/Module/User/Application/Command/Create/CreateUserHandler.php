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

namespace ZenoAuth\Module\User\Application\Command\Create;

use ZenoAuth\Module\User\Domain\Contract\Command\CreateUser;
use ZenoAuth\Module\User\Domain\Service\UserCreatorInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class CreateUserHandler
{
    /**
     * @var UserCreatorInterface
     */
    private $creator;

    /**
     * Constructor.
     *
     * @param UserCreatorInterface $creator
     */
    public function __construct(UserCreatorInterface $creator)
    {
        $this->creator = $creator;
    }

    public function handle(CreateUser $message): void
    {
        $this->creator->create($message);
    }
}
