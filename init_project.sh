#!/bin/bash

export COMPOSE_INTERACTIVE_NO_CLI=1

echo '########init project...'

cp .env.example .env

docker-compose up -d

docker-compose exec laravel.test bash -c "composer update && php artisan jwt:secret && php artisan key:generate && php artisan migrate && php artisan config:cache && php artisan route:cache && php artisan db:seed && php artisan test"

