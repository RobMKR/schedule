Schedule API

## Requirements
- Docker Should be enough

## Installation

- Clone the project to the directory you wish
- `cd schedule`
- Run command to run container for installing dependencies
```
  docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```
- Copy env: `cp .env.example .env`
- Run `vendor/bin/sail up -d`
- Wait for 20-30 sec until mysql will be ready for connections
- Run `vendor/bin/sail artisan migrate`
- Run `vendor/bin/sail artisan key:generate`
- Run `vendor/bin/sail artisan jwt:secret`
- Run `vendor/bin/sail test` to run tests
- Add postman collection to postman
- Enjoy

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
