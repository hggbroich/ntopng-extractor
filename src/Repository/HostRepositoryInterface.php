<?php

namespace App\Repository;

use App\Entity\Host;
use DateTime;

interface HostRepositoryInterface {

    /**
     * @return PaginatedResult<Host>
     */
    public function find(PaginationQuery $paginationQuery, string|null $ip = null): PaginatedResult;

    public function purgeOlderThan(DateTime $threshold): int;
}
