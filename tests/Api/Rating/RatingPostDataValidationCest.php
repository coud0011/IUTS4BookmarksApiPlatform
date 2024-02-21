<?php

namespace App\Tests\Api\Rating;

use App\Factory\BookmarkFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class RatingPostDataValidationCest
{
    public function ratingUnicityTest(ApiTester $I): void
    {
        $user = UserFactory::createOne();
        $bookmark = BookmarkFactory::createOne();

        $I->amLoggedInAs($user);

        $I->sendPost('/api/ratings', [
            'bookmark' => $bookmark,
            'user' => $user,
            'value' => 0,
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->sendPost('/api/ratings', [
            'bookmark' => $bookmark,
            'user' => $user,
            'value' => 5,
        ]);
        $I->seeRes
    }
}
