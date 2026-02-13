<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Host {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private(set) int $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $firstSeen;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $lastSeen;

    #[ORM\Column(type: Types::STRING)]
    public string $ip;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public string|null $name;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public string|null $mac;
}

