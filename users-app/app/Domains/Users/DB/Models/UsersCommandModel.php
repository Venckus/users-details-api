<?php

namespace App\Domains\Users\DB\Models;

use App\Models\User;

/**
 * This class is a Command Model according to CQRS pattern
 * Should be used only for write operations
 */
class UsersCommandModel extends User
{
    /**
     * @var string|null
     * if there is a need for performance improvement, implement CQRS
     * by creating DB slave replica server and
     * this should be the name of the DB master connection from config/database.php
     */
    protected $connection = 'pgsql';
}
