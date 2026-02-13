<?php

namespace App\Ntopng;

use Symfony\Component\Serializer\Attribute\SerializedPath;

class ActiveHostsResponse {

    #[SerializedPath('[rsp][currentPage]')]
    public int $currentPage;

    #[SerializedPath('[rsp][perPage]')]
    public int $perPage;

    /**
     * @var Host[]
     */
    #[SerializedPath('[rsp][data]')]
    public array $hosts = [ ];

}
