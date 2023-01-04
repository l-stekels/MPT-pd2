<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\TeamStatistics;
use App\Entity\Statistics;
use App\Entity\Team;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Statistics>
 */
class StatisticsRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistics::class);
    }

    /**
     * @return TeamStatistics[]
     */
    public function getTeamStatistics(): array
    {
        $results = $this->createQueryBuilder('statistic')
            ->leftJoin('statistic.team', 'team')
            ->select('team.name')
            ->addSelect('sum(statistic.points) as points')
            ->addSelect('SUM(
                   CASE WHEN statistic.overtime = 0 and statistic.win = 1 THEN 1 ELSE 0 END
            ) as onTimeWins')
            ->addSelect('SUM(
                   CASE WHEN statistic.overtime = 0 and statistic.win = 0 THEN 1 ELSE 0 END
            ) as onTimeLoses')
            ->addSelect('SUM(
                   CASE WHEN statistic.overtime = 1 and statistic.win = 1 THEN 1 ELSE 0 END
            ) as overTimeWins')
            ->addSelect('SUM(
                   CASE WHEN statistic.overtime = 1 and statistic.win = 0 THEN 1 ELSE 0 END
            ) as overTimeLoses')
            ->addSelect('SUM(statistic.goalsInTime + statistic.goalsInOvertime) as goalsScored')
            ->addSelect('SUM(statistic.goalsLostInTime + statistic.goalsLostInOvertime) as goalsLost')
            ->groupBy('statistic.team')
            ->addOrderBy('points', 'DESC')
            ->getQuery()
            ->getResult();
        $finalResults = [];
        foreach ($results as $key => $result) {
            $finalResults[] = new TeamStatistics($key + 1, ...$result);
        }

        return $finalResults;
    }
}
