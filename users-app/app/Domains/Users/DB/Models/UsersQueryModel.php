<?php

namespace App\Domains\Users\DB\Models;

use App\Models\User;


/**
 * This class is a Query Model according to CQRS pattern
 * Should be used only for read operations
 */
class UsersQueryModel extends User
{

    /**
     * @var string|null
     * if there is a need for performance improvement, implement CQRS
     * by creating DB slave replica server and change this
     * to the name of the DB slave replica connection from config/database.php
     */
    protected $connection = 'pgsql';
}
