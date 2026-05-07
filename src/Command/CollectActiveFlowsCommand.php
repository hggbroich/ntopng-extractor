<?php

namespace App\Command;

use App\Message\StoreFlowMessage;
use App\MessageBus\Buffer;
use App\Ntopng\Client;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('ntopng:flows:collect')]
readonly class CollectActiveFlowsCommand {

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
            $perPage = 1000;

            do {
                $response = $this->client->getFlows($this->interfaceId, $page, $perPage);

                foreach ($response->flows as $flow) {
                    $buffer->add(sprintf('%s#%s', $flow->hashId, $flow->key), $flow);
                }

                $page++;
            } while ($response->totalRows > ($response->currentPage * $response->perPage));

            if($buffer->mustFlush()) {
                foreach($buffer as $flow) {
                    $this->messageBus->dispatch(new StoreFlowMessage($flow));
                }

                $output->writeln(sprintf('%d flows queued for storage', count($response->flows)));
                $buffer->flush();
            }
        }

        return Command::SUCCESS;
    }
}
