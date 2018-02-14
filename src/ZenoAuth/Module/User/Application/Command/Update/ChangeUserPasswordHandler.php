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

use ZenoAuth\Module\User\Domain\Contract\Command\ChangeUserPassword;
use ZenoAuth\Module\User\Domain\Service\UserPasswordUpdaterInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ChangeUserPasswordHandler
{
    /**
     * @var UserPasswordUpdaterInterface
     */
    private $updater;

    /**
     * Constructor.
     *
     * @param UserPasswordUpdaterInterface $updater
     */
    public function __construct(UserPasswordUpdaterInterface $updater)
    {
        $this->updater = $updater;
    }

    public function handle(ChangeUserPassword $message): void
    {
        $this->updater->changePassword($message);
    }
}
