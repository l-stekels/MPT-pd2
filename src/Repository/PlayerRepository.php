<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\PlayerStatistics;
use App\Entity\Player;
use App\Entity\Team;
use Doctrine\ORM\Query\Expr\Join;
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

    /**
     * @return PlayerStatistics[]
     */
    public function getPlayerStatistics(int $limit = 0): array
    {
        $qb = $this->createQueryBuilder('player')
            ->select('player.firstName')
            ->addSelect('player.lastName')
            ->addSelect('player.number')
            ->leftJoin('player.team', 't')
            ->addSelect('t.name as team')
            ->leftJoin('player.goals', 'g')
            ->addSelect('count(DISTINCT g) as goals')
            ->leftJoin('player.assists', 'a')
            ->addSelect('count(DISTINCT a) as assists')
            ->addOrderBy('goals', 'DESC')
            ->addOrderBy('assists', 'DESC')
            ->addGroupBy('player');
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }
        $results = $qb->getQuery()
            ->getScalarResult();
        $finalResults = [];
        foreach ($results as $key => $result) {
            $finalResults[] = new PlayerStatistics($key + 1, ...$result);
        }

        return $finalResults;
    }

    /**
     * @return PlayerStatistics[]
     */
    public function getRudestPlayers(int $limit = 0): array
    {
        $qb = $this->createQueryBuilder('player')
            ->select('player.firstName')
            ->addSelect('player.lastName')
            ->addSelect('player.number')
            ->leftJoin('player.team', 't')
            ->addSelect('t.name as team')
            ->leftJoin('player.penalties', 'penalty')
            ->addSelect('count(penalty) as penalties')
            ->orderBy('penalties', 'DESC')
            ->addGroupBy('player')
            ->having('penalties > 0');
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }
        $results = $qb->getQuery()
            ->getScalarResult();

        $finalResults = [];
        foreach ($results as $key => $result) {
            $finalResults[] = new PlayerStatistics($key + 1, ...$result);
        }

        return $finalResults;
    }

    public function getPlayerStatisticsForTeam(Team $team): array
    {
        $qb = $this->createQueryBuilder('player')
            ->select('player.number')
            ->addSelect('player.firstName')
            ->addSelect('player.lastName')
            ->leftJoin('player.team', 'playerTeam')
            ->leftJoin('playerTeam.games', 'gamesPlayed')
            ->addSelect('count(gamesPlayed) as games')
            ->groupBy('player')
            ->andWhere('player.team = :team')
            ->setParameter('team', $team);
        // Player game time statistics? The amount of time each player has played in main time and overtime
        /// etc.
        /// 


        return $qb->getQuery()
            ->getScalarResult();
    }
}
