<?php
declare(strict_types=1);

namespace App\Domains\Users\Requests;

use App\Models\User;

class UserUpdateData
{
    public function __construct(
        public readonly ?string $first_name,
        public readonly ?string $last_name,
        public readonly ?string $email,
        public readonly ?string $password,
        public readonly ?string $address,
    ){
    }

    public function toArray(): array
    {
        $data = [];

        foreach (get_object_vars($this) as $key => $value) {
            if (!is_null($value)) {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}