<?php

namespace App\Repository;

use App\Entity\Flow;
use DateTime;
use Override;

class FlowRepository extends AbstractRepository implements FlowRepositoryInterface{

    #[Override]
    public function find(PaginationQuery $paginationQuery, ?string $localIp = null, ?string $l4proto = null, ?string $l7proto = null, ?string $hostname): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select('f')
            ->from(Flow::class, 'f')
            ->orderBy('f.lastSeen', 'DESC');

        if($localIp !== null) {
            $qb->andWhere('f.local.ip = :localip')
                ->setParameter('localip', $localIp);
        }

        if($l4proto !== null) {
            $qb->andWhere('f.l4proto = :l4proto')
                ->setParameter('l4proto', $l4proto);
        }

        if($l7proto !== null) {
            $qb->andWhere('f.l7proto = :l7proto')
                ->setParameter('l7proto', $l7proto);
        }

        if($hostname !== null) {
            $qb->andWhere('f.remote.name = :hostname')
                ->setParameter('hostname', $hostname);
        }

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery);
    }

    #[Override]
    public function findL4Proto(): array {
        return $this->em->createQueryBuilder()
            ->select('DISTINCT f.l4proto')
            ->from(Flow::class, 'f')
            ->groupBy('f.l4proto')
            ->where('f.l4proto IS NOT NULL')
            ->orderBy('f.l4proto', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    #[Override]
    public function findL7Proto(): array {
        return $this->em->createQueryBuilder()
            ->select('DISTINCT f.l7proto')
            ->from(Flow::class, 'f')
            ->groupBy('f.l7proto')
            ->where('f.l7proto IS NOT NULL')
            ->orderBy('f.l7proto', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    #[Override]
    public function purgeOlderThan(DateTime $threshold): int {
        return $this->em->createQueryBuilder()
            ->delete(Flow::class, 'f')
            ->where('f.lastSeen < :threshold')
            ->setParameter('threshold', $threshold)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
