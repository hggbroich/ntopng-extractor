<?php

namespace App\Command;

use App\Message\StoreFlowMessage;
use App\Message\StoreHostMessage;
use App\MessageBus\Buffer;
use App\Ntopng\Client;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('ntopng:hosts:collect')]
readonly class CollectActiveHostsCommand {

    public const int FLUSH_THRESHOLD_IN_SECONDS = 30;

    public function __construct(
        private MessageBusInterface $messageBus,
        private Client $client,
        private ClockInterface $clock,
        #[Autowire(env: 'INTERFACE')] private int $interfaceId
    ) {

    }

    public function __invoke(OutputInterface $output): int {
        $buffer = new Buffer(self::FLUSH_THRESHOLD_IN_SECONDS, $this->clock);

        while(true) {
            $page = 1;
            $perPage = 100;

            do {
                $response = $this->client->getLocalHosts($this->interfaceId, $page, $perPage);

                foreach ($response->hosts as $host) {
                    $buffer->add(sprintf('%d#%s', $host->firstSeen, $host->ip), $host);
                }

                $page++;
            } while ($response->perPage < count($response->hosts));

            if($buffer->mustFlush()) {
                foreach($buffer as $host) {
                    $this->messageBus->dispatch(new StoreHostMessage($host));
                }

                $output->writeln(sprintf('%d hosts queued for storage', count($buffer)));
                $buffer->flush();
            }
        }

        return Command::SUCCESS;
    }
}
