<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Goal;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Goal>
 */
class GoalRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Goal::class);
    }
}
