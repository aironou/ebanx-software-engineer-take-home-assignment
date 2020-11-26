# ebanx-software-engineer-take-home-assignment


## Requirements


1. [Docker](https://docs.docker.com/engine/install/)
2. [docker-compose](https://docs.docker.com/compose/install/)


## Install


1. Enter the project root directory
1. Run `cp .env .env.local` to create local environment file
1. Run `cp docker/php/usr/local/etc/php/conf.d/ebanx.ini.dist docker/php/usr/local/etc/php/conf.d/ebanx.ini` to create PHP config file
1. If you want to change any config, edit the new files
1. Run `docker-compose build` to build container image
1. Run `docker-compose run --rm composer install --verbose` to install project dependencies
1. Run `docker-compose run --rm bin/console doctrine:migrations:migrate --no-interaction` to create database


## Server (development environment)


1. Enter the project root directory
1. Run `docker-compose up local-server`