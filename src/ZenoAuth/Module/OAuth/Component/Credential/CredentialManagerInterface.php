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

namespace ZenoAuth\Module\OAuth\Component\Credential;

use Borobudur\Component\Parameter\ParameterInterface;
use ZenoAuth\Module\OAuth\Component\Credential\Grant\CredentialInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface CredentialManagerInterface
{
    public function add(CredentialInterface $grant): void;

    public function grant(ParameterInterface $request): Signature;

    public function has(string $grant): bool;
}
