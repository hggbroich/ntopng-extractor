<?php

namespace App\Command;

use App\Ntopng\Client;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('ntopng:flows:active')]
readonly class ListActiveFlowsCommand {

    public function __construct(private Client $client) { }

    public function __invoke(#[Argument] int $interface, OutputInterface $output): int {
        $response = $this->client->getFlows($interface);
        $flows = $response->flows;

        $page = 2;
        while($response->totalRows > ($response->currentPage*$response->perPage)) {
            $response = $this->client->getFlows($interface, $page);
            $flows = array_merge($flows, $response->flows);

            $page++;
        }

        $table = new Table($output);
        $table->setHeaders(['Local IP', 'Remote IP', 'Remote Port', 'App (L4)', 'App (L7)', 'Hostname', 'Bytes']);

        foreach($flows as $flow) {
            $table->addRow([
                $flow->client->ip,
                $flow->server->ip,
                $flow->server->port,
                $flow->l4protocol,
                $flow->l7protocol,
                $flow->server->name,
                $flow->bytes,
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
