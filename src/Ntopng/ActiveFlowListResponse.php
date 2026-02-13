<?php

namespace App\Ntopng;

use Symfony\Component\Serializer\Attribute\SerializedPath;

class ActiveFlowListResponse {

    #[SerializedPath('[recordsTotal]')]
    public int $totalRecords;

    /**
     * @var ActiveFlowEntry[]
     */
    #[SerializedPath('[rsp]')]
    public array $flows = [ ];
}
