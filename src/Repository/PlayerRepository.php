<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Player>
 */
class PlayerRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findOneByTeamNameAndNumber(string $teamName, int $number): Player
    {
        return $this->createQueryBuilder('player')
            ->leftJoin('player.team', 'team')
            ->andWhere('team.name = :teamName')
            ->andWhere('player.number = :number')
            ->setParameters([
                'teamName' => $teamName,
                'number' => $number,
            ])
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param string $teamName
     * @param array  $numbers
     *
     * @return Player[]
     */
    public function findByTeamNameAndNumbers(string $teamName, array $numbers): array
    {
        return $this->createQueryBuilder('player')
            ->leftJoin('player.team', 'team')
            ->andWhere('team.name = :teamName')
            ->andWhere('player.number in (:numbers)')
            ->setParameters([
                'teamName' => $teamName,
                'numbers' => $numbers,
            ])
            ->getQuery()
            ->getResult();
    }
}
