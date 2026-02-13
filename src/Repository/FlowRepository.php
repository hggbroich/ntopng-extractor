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
            $qb->andWhere(
                $qb->expr()->orX(
                    'f.remote.name LIKE :hostname',
                    'f.info LIKE :hostname'
                )
            )
                ->setParameter('hostname', '%' . $hostname . '%');
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
    public function findInfo(): array {
        return $this->em->createQueryBuilder()
            ->select('DISTINCT f.info')
            ->from(Flow::class, 'f')
            ->groupBy('f.info')
            ->where('f.info IS NOT NULL')
            ->andWhere("f.info != ''")
            ->orderBy('f.info', 'ASC')
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

    #[Override]
    public function computeL4ProtoStatistics(): array {
        return $this->em->createQueryBuilder()
            ->select(['f.l4proto AS proto', 'COUNT(f.l4proto) AS count'])
            ->from(Flow::class, 'f')
            ->groupBy('f.l4proto')
            ->where('f.l4proto IS NOT NULL')
            ->orderBy('f.l4proto', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    #[Override]
    public function computeL7ProtoStatistics(): array {
        return $this->em->createQueryBuilder()
            ->select(['f.l7proto AS proto', 'COUNT(f.l7proto) AS count'])
            ->from(Flow::class, 'f')
            ->groupBy('f.l7proto')
            ->where('f.l7proto IS NOT NULL')
            ->orderBy('f.l7proto', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    #[Override]
    public function countFlows(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(f)')
            ->from(Flow::class, 'f')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
