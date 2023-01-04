<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Referee;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Referee>
 */
class RefereeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referee::class);
    }
}
