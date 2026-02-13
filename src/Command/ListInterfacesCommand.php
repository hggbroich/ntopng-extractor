<?php

namespace App\Command;

use App\Ntopng\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('ntopng:interfaces:list')]
readonly class ListInterfacesCommand {
    public function __construct(private Client $client) { }

    public function __invoke(OutputInterface $output): int {
        $response = $this->client->getInterfaces();

        $table = new Table($output);
        $table->setHeaders(['ID', 'Name']);
        foreach ($response->interfaces as $interface) {
            $table->addRow([$interface->id, $interface->name]);
        }
        $table->render();

        return Command::SUCCESS;
    }
}
