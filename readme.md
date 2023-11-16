# Project

## Tech stack
PHP 8.2, laravel 10, docker

## Task description
Create a simple JSON API with the following functionality:
* User creation: user data is sent as a JSON payload (First name, Last name, Email,
Password and Address). All the fields except address are required and should be
validated. However, if address is sent, it should be saved to a separate table (let’s say
“user_details”).
* User update: same as the user creation, only that it updates the user and its details.
* User delete: Endpoint for user deletion. User details should be deleted too, if exists.
* User list: endpoint for user list, returns all the information about all existing users.
Let’s say, that this is only a small task taken from a very large project. You need to think of it
as a project that will be scaled a lot.

JSON example of user payload:
```
{
    "first_name": "Some name",
    "last_name": "Last name",
    "email": "some@mail.com",
    "address": "Some address, Vilnius, Lithuania"
}
```

## Implementation
For the structure of files DDD approach is used. Main directory is app/Domains.

`app/Domains/Users/DB/Models` directory contains Eloquent Models. User model is extended for CQRS pattern into `UsersCommandModel` for write operations and `UsersQueryModel` for read operations. This pattern is used for scaling - separating write from read logic. Database server layer write operations should use DB master server and all read operations should use DB slave replica server. In this project BD slave replica server is not implemented.

`app/Domains/Users/DB` directory contains `UsersCommand` and `UsersQuery` services that uses `UsersCommandModel` and `UsersQueryModel` accordingly similar to `repository` pattern.

Update endpoint and command is configured to use `PATCH` HTTP method.

Index endpoint have filtering, sorting and pagination.

### Data structure of project
* Added `last_name` to standard laravel user structure and renamed `name` into `first_name`.
* Added `user_details` table for `address` field as suggested in task description. It is expected for one user to have one address and the relation is set accordingly - one to one. But according to example JSON data this table could be called `user_address` and each address field should have its own column - for example: country, region, city, street, house, appartament.


# TODO

* create DDD directory structure:

        |- App/Domains/Users
        |  |- Controllers
        |  |  |- UsersController.php
        |  |- DB
        |  |  |- UsersCommand.php
        |  |  |- UsersQuery.php
        |  |  |- UsersQueryFilter.php
        |  |  |- Models
        |  |  |  |- UsersCommandModel.php
        |  |  |  |- UsersQueryModel.php
        |  |  |  |- UserDetails.php
        |  |- Requests
        |  |  |- UserStoreData.php
        |  |  |- UserStoreRequest.php
        |  |  |- UserUpdateData.php
        |  |  |- UserUpdateRequest.php
        |  |- Resources
        |  |  |- UserResource.php
        |- App/Models
        |  |- User.php
        |  |- HasUuid.php

* index functionality

## Done
* store functionality
* update functionality
* delete functionality
* Models and UUID trait
* Database structure: migrations, models
* setup db env vars