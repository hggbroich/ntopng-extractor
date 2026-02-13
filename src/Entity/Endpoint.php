<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Endpoint {

    #[ORM\Column(type: Types::STRING)]
    public string $ip;

    #[ORM\Column(type: Types::INTEGER)]
    public int $port;

    #[ORM\Column(type: Types::STRING, nullable:  true)]
    public ?string $name = null;
}
