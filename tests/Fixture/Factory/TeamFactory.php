<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Factory;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Team>
 *
 * @method        Team|Proxy                     create(array|callable $attributes = [])
 * @method static Team|Proxy                     createOne(array|callable $attributes = [])
 * @method static Team[]|Proxy[]                 createMany(int $number, $attributes = [])
 * @method static TeamRepository|RepositoryProxy repository()
 */
class TeamFactory extends ModelFactory
{
    protected static function getClass(): string
    {
        return Team::class;
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->city(),
        ];
    }
}
