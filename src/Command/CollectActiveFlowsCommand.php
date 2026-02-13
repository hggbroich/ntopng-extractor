<?php

namespace App\Command;

use App\Message\StoreFlowMessage;
use App\Ntopng\Client;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('ntopng:flows:collect')]
readonly class CollectActiveFlowsCommand {

    public function __construct(
        private MessageBusInterface $messageBus,
        private Client $client,
        #[Autowire(env: 'INTERFACE')] private int $interfaceId
    ) {

    }

    public function __invoke(OutputInterface $output): int {
        while(true) {
            $page = 1;
            $perPage = 1000;

            do {
                $response = $this->client->getFlows($this->interfaceId, $page, $perPage);

                foreach ($response->flows as $flow) {
                    $this->messageBus->dispatch(new StoreFlowMessage($flow));
                }

                $output->writeln(sprintf('%d flows queued for storage', count($response->flows)));

                $page++;
            } while ($response->totalRows > ($response->currentPage * $response->perPage));
        }

        return Command::SUCCESS;
    }
}
