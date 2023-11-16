<?php
declare(strict_types=1);

namespace App\Domains\Users\DB;


use App\Domains\Users\DB\Models\UserDetails;
use App\Domains\Users\Requests\UserStoreData;
use App\Domains\Users\Requests\UserUpdateData;
use App\Models\User;

class UsersCommand
{
    public static function store(UserStoreData $userData): User
    {
        /** @var User $user */
        $user = User::create([
            User::FIRST_NAME => $userData->first_name,
            User::LAST_NAME => $userData->last_name,
            User::EMAIL => $userData->email,
            User::PASSWORD => $userData->password,
        ]);

        if (isset($userData->{UserDetails::ADDRESS})) {
            UserDetails::create([
                UserDetails::USER_ID => $user->id,
                UserDetails::ADDRESS => $userData->address,
            ]);
        }

        return $user;
    }

    public static function update(UserUpdateData $userData, User $user): User
    {
        $user->loadMissing('details');

        $user->update($userData->toArray());

        if (isset($userData->address)) {
            $user->details()
                ->updateOrCreate([], [
                    UserDetails::USER_ID => $user->id,
                    UserDetails::ADDRESS => $userData->address
                ]);
        }

        if (!isset($userData->address) && $user->details()->exists()) {
            $user->details->delete();
        }

        return $user->refresh();
    }

    public static function destroy(User $user): void
    {
        $user->details->delete();
        $user->delete();
    }
}