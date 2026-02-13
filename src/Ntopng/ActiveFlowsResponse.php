<?php

namespace App\Ntopng;

use Symfony\Component\Serializer\Attribute\SerializedPath;

class ActiveFlowsResponse {

    #[SerializedPath('[rsp][currentPage]')]
    public int $currentPage;

    #[SerializedPath('[rsp][perPage]')]
    public int $perPage;

    #[SerializedPath('[rsp][totalRows]')]
    public int $totalRows;

    /**
     * @var Flow[]
     */
    #[SerializedPath('[rsp][data]')]
    public array $flows = [ ];
}
