<?php

namespace App\DataFixtures;

use App\Factory\BookmarkFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RatingFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $repository = UserFactory::repository();

        foreach ($repository->findAll() as $user) {
            $rateNb = random_int(3, 7);
            for ($i = 0; $i <= $rateNb; ++$i) {
                $bookmark = BookmarkFactory::randomRange(1, 1)[0];
                RatingFactory::createOne([
                    'user' => $user,
                    'bookmark' => $bookmark,
                ]);
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BookmarkFixtures::class,
        ];
    }
}
