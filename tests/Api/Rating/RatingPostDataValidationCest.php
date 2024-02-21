<?php

namespace App\Tests\Api\Rating;

use App\Factory\BookmarkFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class RatingPostDataValidationCest
{
    public function ratingUnicityTest(ApiTester $I): void
    {
        $user = UserFactory::createOne();
        $bookmark = BookmarkFactory::createOne();

        $I->amLoggedInAs($user);
    }
}
