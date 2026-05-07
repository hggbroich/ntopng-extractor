<?php

namespace App\MessageHandler;

use App\Entity\Flow;
use App\Message\StoreFlowDetailsMessage;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
readonly class StoreFlowDetailsMessageHandler {

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) { }

    public function __invoke(StoreFlowDetailsMessage $flowMessage): void {
        try {
            $flow = $flowMessage->flowEntry;

            $storedFlow = $this->entityManager->getRepository(Flow::class)->findOneBy([
                'key' => $flow->key,
                'hashId' => $flow->hashId
            ]);

            if ($storedFlow === null) {
                throw new FlowNotFoundException($flow->key, $flow->hashId);
            }

            $storedFlow->info = $flow->info;
            $storedFlow->localMac = $flow->macAddress;

            $this->entityManager->persist($storedFlow);
            $this->entityManager->flush();
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
