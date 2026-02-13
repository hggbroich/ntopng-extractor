<?php

namespace App\MessageHandler;

use App\Entity\Host;
use App\Message\StoreHostMessage;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class StoreHostMessageHandler {

    public function __construct(
        private EntityManagerInterface $entityManager
    ) { }

    public function __invoke(StoreHostMessage $storeHostMessage): void {
        $storedHost = $this->entityManager->getRepository(Host::class)
            ->findOneBy([
                'firstSeen' => DateTimeImmutable::createFromTimestamp($storeHostMessage->host->firstSeen),
                'ip' => $storeHostMessage->host->ip,
            ]);

        if($storedHost === null) {
            $storedHost = new Host();
            $storedHost->firstSeen = DateTimeImmutable::createFromTimestamp($storeHostMessage->host->firstSeen);
            $storedHost->ip = $storeHostMessage->host->ip;
        }

        $storedHost->mac = $storeHostMessage->host->macAddress;
        $storedHost->name = $storeHostMessage->host->name;
        $storedHost->lastSeen = DateTimeImmutable::createFromTimestamp($storeHostMessage->host->lastSeen);

        $this->entityManager->persist($storedHost);
        $this->entityManager->flush();
    }
}
