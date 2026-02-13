<?php

namespace App\Command;

use App\Message\StoreFlowMessage;
use App\Message\StoreHostMessage;
use App\Ntopng\Client;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('ntopng:hosts:collect')]
readonly class CollectActiveHostsCommand {

    public function __construct(private MessageBusInterface $messageBus, private Client $client) {

    }

    public function __invoke(#[Argument] int $interface, OutputInterface $output): int {
        while(true) {
            $page = 1;
            $perPage = 100;

            do {
                $response = $this->client->getLocalHosts($interface, $page, $perPage);

                foreach ($response->hosts as $host) {
                    $this->messageBus->dispatch(new StoreHostMessage($host));
                }

                $output->writeln(sprintf('%d hosts queued for storage', count($response->hosts)));

                $page++;
            } while ($response->perPage < count($response->hosts));
        }

        return Command::SUCCESS;
    }
}
