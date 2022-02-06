<?php

declare(strict_types=1);

namespace App\Content\Doctrine\Repository;

use App\Content\Entity\Response;
use App\Content\Gateway\ResponseGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Response>
 * @template-implements ResponseGateway<Response>
 */
final class ResponseRepository extends ServiceEntityRepository implements ResponseGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Response::class);
    }
}
