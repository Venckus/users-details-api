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
        |  |- Forms
        |  |  |- UsersForm.php
        |  |- Resources
        |  |  |- UserResource.php
        |- App/Models
        |  |- User.php
        |  |- UserDetails.php
        |  |- HasUuid.php

## Done
* Models and UUID trait
* Database structure: migrations, models
* setup db env vars