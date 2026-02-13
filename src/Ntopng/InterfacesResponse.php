<?php

namespace App\Ntopng;

use Symfony\Component\Serializer\Attribute\SerializedName;

class InterfacesResponse {

    /**
     * @var NtopInterface[]
     */
    #[SerializedName('rsp')]
    public array $interfaces = [ ];
}
