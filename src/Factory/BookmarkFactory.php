<?php

namespace App\Factory;

use App\Entity\Bookmark;
use App\Repository\BookmarkRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Bookmark>
 *
 * @method        Bookmark|Proxy                     create(array|callable $attributes = [])
 * @method static Bookmark|Proxy                     createOne(array $attributes = [])
 * @method static Bookmark|Proxy                     find(object|array|mixed $criteria)
 * @method static Bookmark|Proxy                     findOrCreate(array $attributes)
 * @method static Bookmark|Proxy                     first(string $sortedField = 'id')
 * @method static Bookmark|Proxy                     last(string $sortedField = 'id')
 * @method static Bookmark|Proxy                     random(array $attributes = [])
 * @method static Bookmark|Proxy                     randomOrCreate(array $attributes = [])
 * @method static BookmarkRepository|RepositoryProxy repository()
 * @method static Bookmark[]|Proxy[]                 all()
 * @method static Bookmark[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Bookmark[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Bookmark[]|Proxy[]                 findBy(array $attributes)
 * @method static Bookmark[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Bookmark[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class BookmarkFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'creationDate' => self::faker()->dateTimeBetween('now', '+2 year'),
            'description' => self::faker()->paragraph(),
            'isPublic' => self::faker()->boolean(),
            'name' => self::faker()->company(),
            'url' => self::faker()->url(),
        ];
    }

    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Bookmark $bookmark): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Bookmark::class;
    }
}
