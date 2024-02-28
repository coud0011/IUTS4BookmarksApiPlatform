<?php


namespace App\Tests\Api\Rating;

use App\Factory\BookmarkFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class RatingDeleteCest
{
    public function anonymousCanNotDeleteRating(ApiTester $I): void
    {
        UserFactory::createOne();
        BookmarkFactory::createOne();
        RatingFactory::createOne();

        $I->sendDelete('/api/ratings/1');
        $I->seeResponseCodeIs(401);
    }

    public function userCanDeleteOwnRating(ApiTester $I): void
    {
        $user=UserFactory::createOne()->object();
        BookmarkFactory::createOne();
        RatingFactory::createOne(['user'=>$user]);

        $I->amLoggedInAs($user);

        $I->sendDelete('/api/ratings/1');
        $I->seeResponseCodeIs(204);
    }

    public function userCanNotDeleteOtherRatings(ApiTester $I): void
    {
        BookmarkFactory::createOne();
        UserFactory::createOne();
        RatingFactory::createOne();

        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);

        $I->sendDelete('/api/ratings/1');
        $I->seeResponseCodeIs(403);
    }
}
