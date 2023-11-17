<?php

namespace App\Domains\Users\Requests;

class UserIndexData
{
    public function __construct(
        public readonly ?string $first_name,
        public readonly ?string $last_name,
        public readonly ?string $email,
        public readonly ?string $sort_by,
        public readonly ?string $sort,
        public readonly ?int $page,
        public readonly ?int $per_page,
    ){
    }
}