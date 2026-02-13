<?php

namespace App\Ntopng;

use Symfony\Component\Serializer\Attribute\SerializedName;

class NtopInterface {

    #[SerializedName('ifid')]
    public int $id;

    #[SerializedName('ifname')]
    public string $name;
}
