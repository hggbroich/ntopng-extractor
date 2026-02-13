<?php

namespace App\Ntopng;

use Symfony\Component\Serializer\Attribute\SerializedName;

class Host {

    #[SerializedName('first_seen')]
    public int $firstSeen;

    #[SerializedName('last_seen')]
    public int $lastSeen;

    #[SerializedName('ip')]
    public string $ip;

    #[SerializedName('mac')]
    public string|null $macAddress = null;

    #[SerializedName('name')]
    public string|null $name = null;

}
