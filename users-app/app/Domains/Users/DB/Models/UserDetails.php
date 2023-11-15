<?php

namespace App\Domains\Users\DB\Models;

use App\Models\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory, HasUuid;

    public const USER_ID = 'user_id';
    public const ADDRESS = 'address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::USER_ID,
        self::ADDRESS,
    ];
}
