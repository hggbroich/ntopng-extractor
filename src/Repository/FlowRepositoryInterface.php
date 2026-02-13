<?php

namespace App\Repository;

use App\Entity\Flow;
use DateTime;

interface FlowRepositoryInterface {

    /**
     * @return PaginatedResult<Flow>
     */
    public function find(PaginationQuery $paginationQuery, string|null $localIp = null, string|null $l4proto = null, string|null $l7proto = null, string|null $hostname): PaginatedResult;

    /**
     * @return string[]
     */
    public function findL4Proto(): array;

    /**
     * @return string[]
     */
    public function findL7Proto(): array;

    public function purgeOlderThan(DateTime $threshold): int;

    public function computeL4ProtoStatistics(): array;

    public function computeL7ProtoStatistics(): array;

    public function countFlows(): int;

    /**
     * @return string[]
     */
    public function findInfo(): array;
}
