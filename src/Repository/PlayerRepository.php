<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\PlayerStatistics;
use App\Entity\Player;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Stmt\Finally_;

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
     * @param int[] $numbers
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

    /**
     * @return PlayerStatistics[]
     */
    public function getPlayerStatistics(): array
    {
        $results = $this->createQueryBuilder('player')
            ->leftJoin('player.goals', 'g')
            ->leftJoin('player.assists', 'a')
            ->leftJoin('player.team', 't')
            ->select('player.firstName')
            ->addSelect('player.lastName')
            ->addSelect('player.number')
            ->addSelect('t.name as team')
            ->addSelect('count(g) as goals')
            ->addSelect('count(a) as assists')
            ->groupBy('player')
            ->addOrderBy('goals', 'DESC')
            ->addOrderBy('assists', 'DESC')
            ->getQuery()
            ->getScalarResult();
        $finalResults = [];
        foreach ($results as $key => $result) {
            $finalResults[] = new PlayerStatistics($key + 1, ...$result);
        }

        return $finalResults;
    }
}
