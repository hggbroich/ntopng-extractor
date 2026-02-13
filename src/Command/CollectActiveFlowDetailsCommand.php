<?php

namespace App\Command;

use App\Message\StoreFlowDetailsMessage;
use App\Ntopng\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('ntopng:flows:details:collect')]
readonly class CollectActiveFlowDetailsCommand {

    public function __construct(
        private MessageBusInterface $messageBus,
        private Client $client,
        #[Autowire(env: 'INTERFACE')] private int $interfaceId
    ) {

    }

    public function __invoke(OutputInterface $output): int {
        while(true) {
            $start = 0;
            $length = 5000;

            do {
                $response = $this->client->getActiveFlowsList($this->interfaceId, $start, $length);

                foreach ($response->flows as $flow) {
                    $this->messageBus->dispatch(new StoreFlowDetailsMessage($flow));
                }

                $output->writeln(sprintf('%d flow details queued for storage', count($response->flows)));
                $start += $length;
            } while ($response->totalRecords > $start);
        }

        return Command::SUCCESS;
    }
}
