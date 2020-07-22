# Lumen members api

Event participants

# Installation
+ To get started, the following steps needs to be taken:
+ Clone the repo.
+ `cd members` to the project directory.
+ `cp .env.example .env` to use env config file
+ Run `docker-compose up -d` to start the containers.
+ Run `docker-compose exec php-fpm bash`
+ Run `composer install`
+ Run `php artisan jwt:secret`
+ Run `php artisan migrate`
+ Run `php artisan db:seed`
+ Run `php artisan ./vendor/bin/phpunit`