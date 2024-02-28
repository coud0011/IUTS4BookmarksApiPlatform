<?php

namespace App\Tests\Api\Rating;

use App\Factory\BookmarkFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class RatingPatchCest
{
    public function anonymousCanNotModifyRating(ApiTester $I): void
    {
        UserFactory::createOne();
        BookmarkFactory::createOne();
        RatingFactory::createOne();

        $I->sendPatch('/api/ratings/1', [
            'value' => 5,
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function UserCanModifyOwnRating(ApiTester $I): void
    {
        // Initialisation
        $user = UserFactory::createOne()->object();
        BookmarkFactory::createOne();
        RatingFactory::createOne(['user' => $user])->object();

        // Connexion
        $I->amLoggedInAs($user);

        // Vérification de l'initialisation
        $I->sendGet('/api/ratings/1');
        $I->seeResponseEquals('{"@context":"\/api\/contexts\/Rating","@id":"\/api\/ratings\/1","@type":"Rating","id":1,"bookmark":"\/api\/bookmarks\/2","user":"\/api\/users\/1","value":1}');

        // Action à tester : patch d'un rating appartenant à l'utilisateur connecté
        $I->sendPatch('/api/ratings/1', [
            'value' => 5,
        ]);
        $I->seeResponseCodeIsSuccessful();

        // Vérification de la modification
        $I->sendGet('/api/ratings/1');
        $I->seeResponseEquals('{"@context":"\/api\/contexts\/Rating","@id":"\/api\/ratings\/1","@type":"Rating","id":1,"bookmark":"\/api\/bookmarks\/2","user":"\/api\/users\/1","value":5}');
    }

    public function userCanNotModifyOtherRatings(ApiTester $I): void
    {
        BookmarkFactory::createOne();
        UserFactory::createOne();
        RatingFactory::createOne();

        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);

        $I->sendPatch('/api/ratings/1', [
            'value' => 5,
        ]);
        $I->seeResponseCodeIs(403);
    }
}
