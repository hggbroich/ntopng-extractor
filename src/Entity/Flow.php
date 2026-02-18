<?php

namespace App\Entity;

use DateInterval;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\UniqueConstraint(columns: ['hash_id', 'key'])]
class Flow {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private(set) int $id;

    #[ORM\Column(type: Types::STRING)]
    public string $hashId;

    #[ORM\Column(name: '`key`', type: Types::STRING)]
    public string $key;

    #[ORM\Embedded(class: Endpoint::class)]
    private(set) Endpoint $local;

    #[ORM\Embedded(class: Endpoint::class)]
    private(set) Endpoint $remote;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $firstSeen;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $lastSeen;

    #[ORM\Column(type: Types::JSON)]
    public array $asn = [ ];

    #[ORM\Column(type: Types::JSON)]
    public array $geoIP = [ ];

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public string|null $info = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public string|null $localMac = null;

    public DateInterval $duration {
        get {
            return $this->lastSeen->diff($this->firstSeen);
        }
    }

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $l4proto = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $l7proto = null;

    #[ORM\Column(type: Types::BIGINT)]
    public int $bytes = 0;

    public function __construct() {
        $this->local = new Endpoint();
        $this->remote = new Endpoint();
    }
}
