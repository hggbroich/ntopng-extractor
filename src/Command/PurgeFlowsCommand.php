<?php

namespace App\Command;

use App\Repository\FlowRepositoryInterface;
use DateTime;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCommand('ntopng:flows:purge')]
#[AsCronTask('@daily')]
readonly class PurgeFlowsCommand {
    public function __construct(
        private FlowRepositoryInterface $flowRepository,
        private ClockInterface $clock
    ) { }

    public function __invoke(SymfonyStyle $style): int {
        $style->section('Purge flows older than three days');

        $threshold = DateTime::createFromImmutable($this->clock->now()->modify('-3 days'));
        $count = $this->flowRepository->purgeOlderThan($threshold);

        $style->success(sprintf('Purged %d flows', $count));

        return Command::SUCCESS;
    }
}
