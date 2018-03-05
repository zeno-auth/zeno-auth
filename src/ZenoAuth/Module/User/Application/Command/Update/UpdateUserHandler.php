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

namespace ZenoAuth\Module\User\Application\Command\Update;

use ZenoAuth\Module\User\Domain\Contract\Command\UpdateUser;
use ZenoAuth\Module\User\Domain\Service\UserUpdaterInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class UpdateUserHandler
{
    /**
     * @var UserUpdaterInterface
     */
    private $updater;

    /**
     * Constructor.
     *
     * @param UserUpdaterInterface $updater
     */
    public function __construct(UserUpdaterInterface $updater)
    {
        $this->updater = $updater;
    }

    public function handle(UpdateUser $message): void
    {
        $this->updater->update($message);
    }
}
