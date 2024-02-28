<?php

namespace App\Tests\Api\Rating;

use App\Entity\Rating;
use App\Factory\RatingFactory;
use App\Tests\Support\ApiTester;

class RatingGetCest
{
    public function anonymousCanAccessRatingList(ApiTester $I): void
    {
        $I->sendGet('/api/ratings');
        $I->seeResponseCodeIsSuccessful();
    }

    public function anonymousCanAccessRating(ApiTester $I): void
    {
        RatingFactory::createOne();

        $I->sendGet('/api/ratings/1');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Rating::class, '/api/ratings/1');
    }
}
