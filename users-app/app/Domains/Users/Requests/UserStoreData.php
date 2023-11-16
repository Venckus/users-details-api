<?php

namespace App\Domains\Users\Requests;

class UserStoreData
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $address,
    ){
    }
}