<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Factory;

use App\Entity\Game;
use App\Repository\GameRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Game>
 *
 * @method        Game|Proxy                     create(array|callable $attributes = [])
 * @method static Game|Proxy                     createOne(array|callable $attributes = [])
 * @method static Game[]|Proxy[]                 createMany(int $number, $attributes = [])
 * @method static GameRepository|RepositoryProxy repository()
 */
class GameFactory extends ModelFactory
{
    protected static function getClass(): string
    {
        return Game::class;
    }

    protected function getDefaults(): array
    {
        return [
            'date' => new \DateTimeImmutable(),
            'viewers' => self::faker()->numberBetween(1, 10000),
            'stadium' => self::faker()->city(),
        ];
    }
}
