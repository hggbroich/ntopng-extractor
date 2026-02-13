<?php

namespace App\Ntopng;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Serializer\Attribute\SerializedPath;

class Flow {

    #[SerializedName('hash_id')]
    public string $hashId;
    public string $key;

    public Endpoint $client;

    public Endpoint $server;

    #[SerializedName('first_seen')]
    public int $firstSeen;

    #[SerializedName('last_seen')]
    public int $lastSeen;

    #[SerializedPath('[protocol][l4]')]
    public ?string $l4protocol;

    #[SerializedPath('[protocol][l7]')]
    public ?string $l7protocol;

    public int $bytes;
}
