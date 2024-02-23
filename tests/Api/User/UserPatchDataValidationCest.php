<?php

namespace App\Tests\Api\User;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use Codeception\Util\HttpCode;

class UserPatchDataValidationCest
{
    protected static function expectedProperties(): array
    {
        return [
            'id' => 'integer',
            'login' => 'string',
            'firstname' => 'string',
            'lastname' => 'string',
            'email' => 'string:email',
        ];
    }

    // tests
    public function authenticatedUserCanPatchOwnData(ApiTester $I): void
    {
        // 1. 'Arrange'
        $dataInit = [
            'login' => 'authenticated',
        ];

        /** @var $user User */
        $user = UserFactory::createOne($dataInit)->object();
        $I->amLoggedInAs($user);
        $dataInit = [
            'login' => 'login',
        ];

        UserFactory::createOne($dataInit);

        // 2. 'Act'
        $dataPatch = [
            'login' => 'login',
        ];
        $I->sendPatch('/api/users/1', $dataPatch);

        // 3. 'Assert'
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    #[DataProvider('invalidDataLeadsToUnprocessableEntityProvider')]
    public function invalidDataLeadsToUnprocessableEntity(ApiTester $I, Example $example): void
    {
        // 1. 'Arrange'
        /** @var $user User */
        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $data = [$example['property'] => $example['value']];
        $I->sendPatch('/api/users/1', $data);

        // 3. 'Assert'
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    protected function invalidDataLeadsToUnprocessableEntityProvider(): array
    {
        return [
            [
                'property' => 'login',
                'value' => '<',
            ],
            [
                'property' => 'login',
                'value' => '&',
            ],
            [
                'property' => 'login',
                'value' => '"',
            ],
            [
                'property' => 'login',
                'value' => '>',
            ],
            [
                'property' => 'firstname',
                'value' => '<',
            ],
            [
                'property' => 'firstname',
                'value' => '&',
            ],
            [
                'property' => 'firstname',
                'value' => '"',
            ],
            [
                'property' => 'firstname',
                'value' => '>',
            ],
            [
                'property' => 'lastname',
                'value' => '<',
            ],
            [
                'property' => 'lastname',
                'value' => '&',
            ],
            [
                'property' => 'lastname',
                'value' => '"',
            ],
            [
                'property' => 'lastname',
                'value' => '>',
            ],
            [
                'property' => 'email',
                'value' => 'bad mail',
            ],
            [
                'property' => 'email',
                'value' => 'mail.fr',
            ],
            [
                'property' => 'email',
                'value' => 'mail@example',
            ],
        ];
    }
}
