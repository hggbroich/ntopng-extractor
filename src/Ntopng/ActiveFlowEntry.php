<?php

namespace App\Ntopng;

use Symfony\Component\Serializer\Attribute\SerializedPath;

class ActiveFlowEntry {
    public string $hashId;

    public string $key;

    #[SerializedPath('[client][mac]')]
    public string|null $macAddress = null;

    public string|null $info = null;
}
