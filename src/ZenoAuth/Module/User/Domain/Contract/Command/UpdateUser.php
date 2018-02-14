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

namespace ZenoAuth\Module\User\Domain\Contract\Command;

use Borobudur\Component\Messaging\Message\Command;
use Borobudur\Component\Value\User\Email;
use ZenoAuth\Module\User\Domain\Entity\UserId;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class UpdateUser extends Command
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $name;

    public function getId(): UserId
    {
        return new UserId($this->id);
    }

    public function getEmail(): Email
    {
        return new Email($this->email);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
