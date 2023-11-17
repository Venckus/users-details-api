<?php

namespace Tests\Feature\Domains\Users\Controllers;

use App\Domains\Users\DB\Models\UserDetails;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array<string, string>
     */
    private array $userDataSample = [
        'first_name' => 'test name',
        'last_name' => 'test last name',
        'email' => 'name@mail.com',
        'password' => '123456',
        'address' => 'test address',
    ];

    public function testShouldCreateUserWithAddress(): void
    {
        $userData = $this->userDataSample;

        $response = $this->postJson('/api/users', $userData);

        unset($userData['password']);

        $response->assertStatus(201);
        $response->assertJson($userData);

        $this->assertDatabaseHas('user_details', [
            'address' => $userData['address'],
        ]);
    }

    public function testShouldCreateUserWithoutAddress(): void
    {
        $userData = $this->userDataSample;

        unset($userData['address']);

        $response = $this->postJson('/api/users', $userData);

        unset($userData['password']);

        $response->assertStatus(201);
        $response->assertJson($userData);

        $this->assertDatabaseCount('user_details', 0);
    }

    public function testShouldNotCreate2UsersWithSameEmail(): void
    {
        $userData = $this->userDataSample;

        $this->postJson('/api/users', $userData);

        $response = $this->postJson('/api/users', $userData);

        unset($userData['password']);

        $response->assertStatus(422);
        $response->assertJsonFragment(['errors' => ['email' => ['The email has already been taken.']]]);
    }

    /**
     * @dataProvider validateUserDataProvider
     */
    public function testShouldValidateData($userData, $error): void
    {
        $response = $this->postJson('/api/users', $userData);

        unset($userData['password']);

        $response->assertStatus(422);
        $response->assertJsonFragment(['errors' => $error]);
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function validateUserDataProvider(): array
    {
        return [
            'without first_name' => [
                [
                    'last_name' => 'test last name',
                    'email' => 'mail@mail.com',
                    'password' => '123456',
                ],
                ['first_name' => ['The first name field is required.']],
            ],
            'first_name empty string' => [
                [
                    'first_name' => '',
                    'last_name' => 'test last name',
                    'email' => 'name@mail.com',
                    'password' => '123456',
                ],
                ['first_name' => ['The first name field is required.']],
            ],
            'without last_name' => [
                [
                    'first_name' => 'test name',
                    'email' => 'mail@mail.com',
                    'password' => '123456',
                ],
                ['last_name' => ['The last name field is required.']],
            ],
            'last_name empty string' => [
                [
                    'first_name' => 'test name',
                    'last_name' => '',
                    'email' => 'name@mail.com',
                    'password' => '123456',
                ],
                ['last_name' => ['The last name field is required.']],
            ],
            'without password' => [
                [
                    'first_name' => 'test name',
                    'last_name' => 'test last name',
                    'email' => 'mail@mail.com',
                ],
                ['password' => ['The password field is required.']],
            ],
            'password empty string' => [
                [
                    'first_name' => 'test name',
                    'last_name' => 'test last name',
                    'email' => 'name@mail.com',
                    'password' => '',
                ],
                ['password' => ['The password field is required.']],
            ],
            'without email' => [
                [
                    'first_name' => 'test name',
                    'last_name' => 'test last name',
                    'password' => '123456',
                ],
                ['email' => ['The email field is required.']],
            ],
            'email empty string' => [
                [
                    'first_name' => 'test name',
                    'last_name' => 'test last name',
                    'email' => '',
                    'password' => '123456',
                ],
                ['email' => ['The email field is required.']],
            ],
            'email without domain' => [
                [
                    'first_name' => 'test name',
                    'last_name' => 'test last name',
                    'email' => 'mail@',
                    'password' => '123456',
                ],
                ['email' => ['The email field must be a valid email address.']],
            ],
            'email without @ symbol' => [
                [
                    'first_name' => 'test name',
                    'last_name' => 'test last name',
                    'email' => 'namemail.com',
                    'password' => '123456',
                ],
                ['email' => ['The email field must be a valid email address.']],
            ],
        ];
    }

    public function testShouldUpdateUserWithoutAddress(): void
    {
        $userData = $this->userDataSample;

        unset($userData['address']);

        $user = User::create($userData);

        $userData['first_name'] = 'updated name';

        $response = $this->json(
            method: 'PATCH',
            uri: "/api/users/{$user->uuid}",
            data: $userData
        );

        unset($userData['password']);

        $userData['uuid'] = $user->uuid;

        $response->assertStatus(200);
        $response->assertJson($userData);
    }


    public function testShouldUpdateUserWithAddress(): void
    {
        $userData = $this->userDataSample;

        $user = User::create($userData);

        $userData['first_name'] = 'updated name';

        $response = $this->json(
            method: 'PATCH',
            uri: "/api/users/{$user->uuid}",
            data: $userData
        );

        unset($userData['password']);

        $userData['uuid'] = $user->uuid;

        $response->assertStatus(200);
        $response->assertJson($userData);
    }


    public function testShouldUpdateUserDeleteAddress(): void
    {
        $userData = $this->userDataSample;

        $user = $this->setupUserWithDetails();

        unset($userData['address']);

        $response = $this->json(
            method: 'PATCH',
            uri: "/api/users/{$user->uuid}",
            data: $userData
        );

        $userData['uuid'] = $user->uuid;

        unset($userData['password']);

        $response->assertStatus(200);
        $response->assertJson($userData);

        $this->assertDatabaseCount('user_details', 0);
    }

    public function testShouldDeleteUserAndDetails(): void
    {
        $user = $this->setupUserWithDetails();

        $response = $this->json(
            method: 'DELETE',
            uri: "/api/users/{$user->uuid}"
        );

        $response->assertStatus(204);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('user_details', 0);
    }

    public function testShouldReturnUsers(): void
    {
        User::factory()->count(3)->create();

        $response = $this->json(
            method: 'GET',
            uri: '/api/users'
        );

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'first_name',
                    'last_name',
                    'email',
                    'address',
                ],
            ],
        ]);
    }

    /**
     * @dataProvider indexFilterDataProvider
     * @param string[][] $paramsArray
     */
    public function testShouldFilterUsers(array $paramsArray): void
    {
        User::create([
            'first_name' => 'test name',
            'last_name' => 'test last name',
            'email' => 'test@mail.com',
            'password' => '123456',
        ]);
        $user2 = User::create([
            'first_name' => 'abc name',
            'last_name' => 'abc last name',
            'email' => 'abc@mail.com',
            'password' => '123456',
        ]);

        $response = $this->json(
            method: 'GET',
            uri: '/api/users',
            data: $paramsArray
        );

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                [
                    'uuid' => $user2->uuid,
                    'first_name' => $user2->first_name,
                    'last_name' => $user2->last_name,
                    'email' => $user2->email,
                ],
            ],
        ]);
    }

    /**
     * @return string[][]
     */
    public static function indexFilterDataProvider(): array
    {
        return [
            'filter by first_name' => [
                ['first_name' => 'abc name'],
            ],
            'filter by last_name' => [
                ['last_name' => 'abc last name'],
            ],
            'filter by email' => [
                ['email' => 'abc@mail.com'],
            ],
            'filter by first_name and last_name' => [
                [
                    'first_name' => 'abc name',
                    'last_name' => 'abc last name',
                ],
            ],
            'filter by first_name and email' => [
                [
                    'first_name' => 'abc name',
                    'email' => 'abc@mail.com',
                ],
            ],
        ];
    }

    /**
     * @dataProvider indexSortDataProvider
     */
    public function testShouldSortUsers(
        string $sortBy,
        string $sort,
        string $jsonPath1,
        string $jsonPath2,
        string $jsonPath3,
    ): void {
        $user1 = User::create([
            'first_name' => 'test name',
            'last_name' => 'test last name',
            'email' => 'test@mail.com',
            'password' => '123456',
        ]);
        $user2 = User::create([
            'first_name' => 'abc name',
            'last_name' => 'abc last name',
            'email' => 'abc@mail.com',
            'password' => '123456',
        ]);
        $user3 = User::create([
            'first_name' => 'xyz name',
            'last_name' => 'xyz last name',
            'email' => 'xyz@mail.com',
            'password' => '123456',
        ]);

        $response = $this->json(
            method: 'GET',
            uri: '/api/users',
            data: [
                'sort_by' => $sortBy,
                'sort' => $sort,
            ]
        );

        $response->assertStatus(200);
        $response->assertJsonPath($jsonPath1, $user1->$sortBy);
        $response->assertJsonPath($jsonPath2, $user2->$sortBy);
        $response->assertJsonPath($jsonPath3, $user3->$sortBy);
    }

    /**
     * @return string[][]
     */
    public static function indexSortDataProvider(): array
    {
        return [
            'sort by first_name desc' => [
                'first_name',
                'desc',
                'data.1.first_name',
                'data.2.first_name',
                'data.0.first_name',
            ],
            'sort by last_name asc' => [
                'last_name',
                'asc',
                'data.1.last_name',
                'data.0.last_name',
                'data.2.last_name',
            ],
            'sort by email desc' => [
                'email',
                'desc',
                'data.1.email',
                'data.2.email',
                'data.0.email',
            ],
        ];
    }

    public function testShouldPaginateUsers(): void
    {
        User::factory()->count(15)->create();

        $response = $this->json(
            method: 'GET',
            uri: '/api/users'
        )->assertStatus(200);

        $response->assertJsonCount(10, 'data');

        $response = $this->json(
            method: 'GET',
            uri: '/api/users',
            data: ['page' => 2]
        )->assertStatus(200);

        $response->assertJsonCount(5, 'data');
    }

    private function setupUserWithDetails(): User
    {
        $userData = $this->userDataSample;

        $user = User::create($userData);
        $user->details()->create([UserDetails::ADDRESS => $userData['address']]);

        return $user;
    }

}