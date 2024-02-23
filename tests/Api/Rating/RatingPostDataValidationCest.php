<?php

namespace App\Tests\Api\Rating;

use App\Factory\BookmarkFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class RatingPostDataValidationCest
{
    public function ratingUnicityTest(ApiTester $I): void
    {
        $user = UserFactory::createOne()->object();
        $bookmark = BookmarkFactory::createOne();

        $I->amLoggedInAs($user);

        $I->sendPost('/api/ratings', [
            'bookmark' => '/api/bookmarks/1',
            'user' => '/api/users/1',
            'value' => 0,
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->sendPost('/api/ratings', [
            'bookmark' => '/api/bookmarks/1',
            'user' => '/api/users/1',
            'value' => 5,
        ]);
        $I->seeResponseCodeIs(422);
    }

    public function valueValidationTest(ApiTester $I): void
    {
        $user = UserFactory::createOne()->object();
        BookmarkFactory::createOne();
        BookmarkFactory::createOne();

        $I->amLoggedInAs($user);

        $I->sendPost('/api/ratings', [
            'bookmark' => '/api/bookmarks/1',
            'user' => '/api/users/1',
            'value' => -1,
        ]);
        $I->seeResponseCodeIs(422);
        $I->sendPost('/api/ratings', [
            'bookmark' => '/api/bookmarks/2',
            'user' => '/api/users/1',
            'value' => 11,
        ]);
        $I->seeResponseCodeIs(422);
    }
}
