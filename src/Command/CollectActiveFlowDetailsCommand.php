<?php

namespace App\Command;

use App\Message\StoreFlowDetailsMessage;
use App\MessageBus\Buffer;
use App\Ntopng\Client;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('ntopng:flows:details:collect')]
readonly class CollectActiveFlowDetailsCommand {

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
            $start = 0;
            $length = 5000;

            do {
                $response = $this->client->getActiveFlowsList($this->interfaceId, $start, $length);

                foreach ($response->flows as $flow) {
                    $buffer->add($flow->hashId, $flow);
                }

                $start += $length;
            } while ($response->totalRecords > $start);

            if($buffer->mustFlush()) {
                foreach($buffer as $flow) {
                    $this->messageBus->dispatch(new StoreFlowDetailsMessage($flow));
                }

                $output->writeln(sprintf('%d flow details queued for storage', count($buffer)));
                $buffer->flush();
            }
        }

        return Command::SUCCESS;
    }
}
