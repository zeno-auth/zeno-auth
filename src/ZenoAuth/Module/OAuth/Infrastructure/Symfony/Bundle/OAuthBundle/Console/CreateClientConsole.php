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

namespace ZenoAuth\Module\OAuth\Infrastructure\Symfony\Bundle\OAuthBundle\Console;

use Borobudur\Component\Messaging\Bus\MessageBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZenoAuth\Module\OAuth\Domain\Contract\Command\CreateClient;
use ZenoAuth\Module\OAuth\Domain\Entity\Client;
use ZenoAuth\Module\OAuth\Domain\Repository\ClientRepositoryInterface;
use ZenoAuth\Shared\Value\Uris;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class CreateClientConsole extends Command
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @var ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * Constructor.
     *
     * @param MessageBusInterface       $bus
     * @param ClientRepositoryInterface $clientRepository
     */
    public function __construct(MessageBusInterface $bus, ClientRepositoryInterface $clientRepository)
    {
        parent::__construct();

        $this->bus = $bus;
        $this->clientRepository = $clientRepository;
    }

    protected function configure()
    {
        $this
            ->setName('client:create')
            ->addArgument('user', InputArgument::REQUIRED, 'User ID')
            ->addArgument('name', InputArgument::REQUIRED, 'Client name')
            ->addOption('trust', 't', InputOption::VALUE_NONE, 'Mark client as trusted')
            ->addOption('grant', 'g', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Add grant type')
            ->addOption('redirect', 'r', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Redirect uris')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = new CreateClient($input->getArguments());
        $this->bus->dispatch($command);

        /** @var Client $client */
        $client = $this->clientRepository->find($command->getId());

        $client->setTrusted($input->getOption('trust'));

        if (!empty($input->getOption('grant'))) {
            $client->setAllowedGrantTypes(array_merge($client->getAllowedGrantTypes(), $input->getOption('grant')));
        }

        if (!empty($input->getOption('redirect'))) {
            $client->setRedirectUris(Uris::fromArray($input->getOption('redirect')));
        }

        $this->clientRepository->save($client);

        $table = new Table($output);

        $table
            ->setRows(
                [
                    ['ID', (string) $client->getId()],
                    ['Name', $client->getName()],
                    ['User', (string) $client->getUser()->getUsername()],
                    ['Secret', $client->getSecret()],
                    ['Redirect Uris', $this->transformRedirectUris($client->getRedirectUris())],
                    ['Grant Types', json_encode($client->getAllowedGrantTypes())],
                    ['Trusted', $client->isTrusted() ? 'Yes' : 'No'],
                ]
            )
        ;

        $table->render();
    }

    private function transformRedirectUris(?Uris $redirectUris): string
    {
        if (null === $redirectUris) {
            return '';
        }

        $transformed = [];

        foreach ($redirectUris->all() as $uri) {
            $transformed[] = (string) $uri;
        }

        return json_encode($transformed);
    }
}
