<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Penalty;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Penalty>
 */
class PenaltyRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Penalty::class);
    }
}
