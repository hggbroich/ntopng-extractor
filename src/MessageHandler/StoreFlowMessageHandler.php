<?php

namespace App\MessageHandler;

use App\Entity\Flow;
use App\Message\StoreFlowMessage;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use GeoIp2\Model\Asn;
use GpsLab\Bundle\GeoIP2Bundle\Reader\ReaderFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
readonly class StoreFlowMessageHandler {

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReaderFactory $readerFactory
    ) { }

    public function __invoke(StoreFlowMessage $flowMessage): void {
        $flow = $flowMessage->flow;

        $storedFlow = $this->entityManager->getRepository(Flow::class)->findOneBy([
            'key' => $flow->key,
            'hashId' =>  $flow->hashId
        ]);

        if($storedFlow === null) {
            $storedFlow = new Flow();
            $storedFlow->hashId = $flow->hashId;
            $storedFlow->key = $flow->key;
        }

        $storedFlow->local->ip = $flow->client->ip;
        $storedFlow->local->port = $flow->client->port;
        $storedFlow->local->name = $flow->client->name;

        $storedFlow->remote->ip = $flow->server->ip;
        $storedFlow->remote->port = $flow->server->port;
        $storedFlow->remote->name = $flow->server->name;

        $storedFlow->l4proto = $flow->l4protocol;
        $storedFlow->l7proto = $flow->l7protocol;
        $storedFlow->bytes = $flow->bytes;

        $storedFlow->firstSeen = DateTimeImmutable::createFromTimestamp($flow->firstSeen);
        $storedFlow->lastSeen = DateTimeImmutable::createFromTimestamp($flow->lastSeen);

        $storedFlow->asn = $this->getASN($flow->server->ip);
        $storedFlow->geoIP = $this->getGeoIP($flow->server->ip);

        $this->entityManager->persist($storedFlow);
        $this->entityManager->flush();
    }

    private function getASN(string $ip): array {
        try {
            $reader = $this->readerFactory->create('asn', ['de']);
            $asn = $reader->asn($ip);

            return $asn->jsonSerialize();
        } catch (Throwable) {
            return [ ];
        }
    }

    private function getGeoIP(string $ip): array {
        try {
            $reader = $this->readerFactory->create('country', ['de']);
            $asn = $reader->country($ip);

            return $asn->jsonSerialize();
        } catch (Throwable) {
            return [ ];
        }
    }
}
