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

namespace ZenoAuth\Web\Infrastructure\Symfony\Http\Controller;

use Borobudur\Component\Exception\Exception;
use Borobudur\Component\Messaging\Bus\MessageBusInterface;
use Borobudur\Component\Parameter\ImmutableParameter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use ZenoAuth\Module\OAuth\Component\Authorization\AuthorizationInterface;
use ZenoAuth\Module\OAuth\Component\UriMakerTrait;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\GrantClient;
use ZenoAuth\Module\OAuth\Domain\Contract\Query\FindAllScopesByIdentifiers;
use ZenoAuth\Module\OAuth\Domain\Contract\Query\FindClientById;
use ZenoAuth\Module\OAuth\Domain\Contract\Query\FindGrantedClient;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\OAuth\Domain\Entity\GrantedClient;
use ZenoAuth\Module\OAuth\Domain\Model\Scopes;
use ZenoAuth\Module\User\Domain\Entity\User;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class OAuthController extends Controller
{
    const AUTHORIZE_PARAM = '_authorize_params';

    use UriMakerTrait;

    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * Constructor.
     *
     * @param MessageBusInterface    $bus
     * @param AuthorizationInterface $authorization
     */
    public function __construct(MessageBusInterface $bus, AuthorizationInterface $authorization)
    {
        $this->bus = $bus;
        $this->authorization = $authorization;
    }

    /**
     * @Route("oauth2/authorize", name="zeno_oauth_prepare_authorize")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function prepareAuthorize(Request $request): Response
    {
        $this->authorization->verify(new ImmutableParameter($request->query->all()));

        $requestScopes = Scopes::fromString($request->get('scopes', 'basic'));
        $client = $this->bus->dispatch(new FindClientById(['id' => $request->get('client_id')]));
        $granted = $this->findGrantedClient($this->getUser()->getUser(), $client);

        if (null !== $granted || $client->isTrusted()) {
            if ($client->isTrusted() || !$granted->getScopes()->hasDiff($requestScopes)) {
                $params = new ImmutableParameter($request->query->all());

                return new RedirectResponse($this->performAuthorize($params));
            }

            $requestScopes = Scopes::fromArray($granted->getScopes()->diff($requestScopes));
        }

        $scopes = $this->bus->dispatch(new FindAllScopesByIdentifiers(['scopes' => (string) $requestScopes]));

        $redirectUri = $request->get('continue', (string) $client->getRedirectUris()->first());

        $request->getSession()->set(self::AUTHORIZE_PARAM, json_encode($request->query->all()));

        return $this->render(
            '@ZenoAuthWeb/auth/pages/authorize.page.html.twig',
            [
                'scopes'              => $scopes,
                'user'                => $this->getUser()->getUser(),
                'client'              => $client,
                'cancel_redirect_uri' => $this->makeRedirectUri(
                    $redirectUri,
                    [
                        'error'       => 'access_denied',
                        'description' => 'The user denied access to your application',
                    ]
                ),
            ]
        );
    }

    /**
     * @Route("oauth2/authorize", name="zeno_oauth_authorize")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function authorize(Request $request): JsonResponse
    {
        try {
            $content = $request->getContent();
            $data = json_decode($content, true);
            $token = isset($data['_csrf_token']) ? $data['_csrf_token'] : null;

            if (!$this->isCsrfTokenValid('authorize', $token)) {
                throw new InvalidCsrfTokenException('Invalid CSRF token.');
            }

            $params = new ImmutableParameter(json_decode($request->getSession()->get(self::AUTHORIZE_PARAM), true));
            $redirectUri = $this->performAuthorize($params);

            $this->bus->dispatch(
                new GrantClient(
                    [
                        'client' => $params->get('client_id'),
                        'user'   => (string) $this->getUser()->getUser()->getId(),
                        'scopes' => $params->get('scopes'),
                    ]
                )
            );

            return new JsonResponse(['redirect_uri' => $redirectUri]);
        } catch (\Exception $e) {
            $error = ['error' => $e->getMessage()];
            $code = 400;

            if ($e instanceof Exception) {
                $code = $e->getCode();
            }

            return new JsonResponse($error, $code);
        }
    }

    /**
     * Perform authorization and create redirect uri.
     *
     * @param ImmutableParameter $params
     *
     * @return string
     */
    private function performAuthorize(ImmutableParameter $params): string
    {
        $this->authorization->verify($params);

        $authorized = $this->authorization->authorize($this->getUser()->getUser(), $params);
        $redirectUri = $this->makeRedirectUri($authorized->getRedirectUri(), $authorized->getParams());

        return $redirectUri;
    }

    /**
     * @param User   $user
     * @param Client $client
     *
     * @return GrantedClient
     */
    private function findGrantedClient(User $user, Client $client): ?GrantedClient
    {
        return $this->bus->dispatch(
            new FindGrantedClient(
                [
                    'user'   => (string) $user->getId(),
                    'client' => (string) $client->getId(),
                ]
            )
        );
    }
}
