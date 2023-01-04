<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Substitution;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Substitution>
 */
class SubstitutionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Substitution::class);
    }
}
