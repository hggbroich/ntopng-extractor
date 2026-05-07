<?php

namespace App\MessageHandler;

use App\Entity\Flow;
use App\Message\StoreFlowDetailsMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class StoreFlowDetailsMessageHandler {

    public function __construct(
        private EntityManagerInterface $entityManager
    ) { }

    public function __invoke(StoreFlowDetailsMessage $flowMessage): void {
        $flow = $flowMessage->flowEntry;

        $storedFlow = $this->entityManager->getRepository(Flow::class)->findOneBy([
            'key' => $flow->key,
            'hashId' =>  $flow->hashId
        ]);

        if($storedFlow === null) {
            throw new FlowNotFoundException($flow->key, $flow->hashId);
        }

        $storedFlow->info = $flow->info;
        $storedFlow->localMac = $flow->macAddress;

        $this->entityManager->persist($storedFlow);
        $this->entityManager->flush();
    }
}
