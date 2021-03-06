<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Repository;

use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<World>
 * @template-implements WorldGateway<World>
 */
final class WorldRepository extends ServiceEntityRepository implements WorldGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, World::class);
    }

    public function getWorlds(): array
    {
        return $this->createQueryBuilder('w')
            ->addSelect('c')
            ->addSelect('r')
            ->join('w.continents', 'c')
            ->join('c.regions', 'r')
            ->getQuery()
            ->getResult();
    }
}
