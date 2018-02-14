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

namespace ZenoAuth\Api\Module\OAuth\Infrastructure\Symfony\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class UserController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $data = [
            'data' => $this->getUser()->toArray(),
            'meta' => ['version' => $request->get('version')],
        ];

        return new JsonResponse($data);
    }
}
