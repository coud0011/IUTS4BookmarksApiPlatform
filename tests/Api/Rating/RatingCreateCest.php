<?php

namespace App\Tests\Api\Rating;

use App\Entity\Rating;
use App\Factory\BookmarkFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class RatingCreateCest
{
    public function anonymousCanNotCreateRating(ApiTester $I): void
    {
        BookmarkFactory::createOne();
        UserFactory::createOne();
        $I->sendPost('/api/ratings', [
            'bookmark' => '/api/bookmarks/1',
            'user' => '/api/users/1',
            'value' => 0,
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function authenticatedUserCanCreateRating(ApiTester $I): void
    {
        BookmarkFactory::createOne();
        $user = UserFactory::createOne()->object();

        $I->amLoggedInAs($user);

        $I->sendPost('/api/ratings', [
            'bookmark' => '/api/bookmarks/1',
            'user' => '/api/users/1',
            'value' => 0,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->sendGet('/api/ratings/1');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Rating::class, '/api/ratings/1');
    }
}
