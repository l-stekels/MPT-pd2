<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Factory;

use App\Entity\Statistics;
use App\Repository\StatisticsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Statistics>
 *
 * @method        Statistics|Proxy                     create(array|callable $attributes = [])
 * @method static Statistics|Proxy                     createOne(array|callable $attributes = [])
 * @method static Statistics[]|Proxy[]                 createMany(int $number, $attributes = [])
 * @method static StatisticsRepository|RepositoryProxy repository()
 */
class StatisticsFactory extends ModelFactory
{
    protected static function getClass(): string
    {
        return Statistics::class;
    }

    protected function getDefaults(): array
    {
        return [
            'team' => TeamFactory::new(),
            'goalsInTime' => 1,
            'goalsInOverTime' => 0,
            'win' => true,
            'overTime' => false,
        ];
    }
}
