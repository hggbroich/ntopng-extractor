<?php

namespace App\Repository;

use App\Entity\Host;
use DateTime;
use Override;

class HostRepository extends AbstractRepository implements HostRepositoryInterface {

    #[Override]
    public function find(PaginationQuery $paginationQuery, ?string $ip = null): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select('h')
            ->from(Host::class, 'h')
            ->orderBy('h.ip', 'ASC');

        if(!empty($ip)) {
            $qb->where('h.ip = :ip')
                ->setParameter('ip', $ip);
        }

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery);
    }

    #[Override]
    public function purgeOlderThan(DateTime $threshold): int {
        return $this->em->createQueryBuilder()
            ->delete(Host::class, 'h')
            ->where('h.lastSeen < :threshold')
            ->setParameter('threshold', $threshold)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
