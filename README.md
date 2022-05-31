Schedule API

## Requirements
- Docker Should be enough

## Installation

- Clone the project to the directory you wish
- `cd [directory]`
- `composer install`
- `cp .env.example .env`.
- Update .env database section and set you credentials
- Update .env cache section and put redis/memcached credentials if you have, if no it will use you hard drive as cache storage.
- Run `php artisan key:generate` to generate new key for app.
- Run `php artisan jwt:secret` to generate new key for jwt auth.
- Run `php artisan migrate` to run a migrations
- Run `sudo -u [Your nginx or apache user, commonly www-data] php artisan serve` Most of cases your user will not have access to /tmp 
directory which is required for hosting laravel as itself without use of apache or nginx


## Versioning
Currently there is only one version supported [V1]
But all files and structure are done for supporting more versions
V1, V2, V3 folders, route files and middleware for switching betweem the is created.

## Auth
We have used JWT Auth [https://github.com/tymondesigns/jwt-auth] for authenitcating users

## Admin User
We will create default ADMIN user under `admin@invygo.com` and password will be from .env (default: 12345678)

## Accumulated filters
`/api/v1/schedule/accumulated?period=y&sort=DESC` 
Accumulated request supports 2 filters: `period` and `sort`
`period` can be y/m/q/w | sort can be ASC/DESC

## Routes
Routes will be in project: Schedule.postman_collection.json (import to postman, and directly use)

## Note
Do not forget to put `Accept: application/json` and `Content-Type: application/json` in request headers, to tell our api that you are requesting json responses..
