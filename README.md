# BookStore training project

## Requirements

- PHP 7.4
- Symfony 4
- PostgreSQL
- JSON
- PHPUnit
- Postman
- Apache 

## Structure
### Source
`/bookstore`
### Postman
`/postman`

## Installation
Run Composer to install the required package:

`composer install`

Change connection database in `/.env`

`DATABASE_URL=postgresql://db_username:db_password@127.0.0.1:5432/db_name?serverVersion=13&charset=utf8`

Run migration

`php bin/console doctrine:database:create`

`php bin/console doctrine:migrations:migrate`

Start server

`symfony server:start`

## Run test case

Create the test database

`php bin/console --env=test doctrine:database:create`

Create the table/columns in the test database

`php bin/console --env=test doctrine:schema:create`

Run test:

`php bin/phpunit`
