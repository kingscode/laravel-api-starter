# Laravel Api Starter

[![Build Status](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/badges/build.png?b=master)](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/?branch=master)

Our base `laravel/laravel` installation for `vue` front-end applications.

## Usage
```bash
composer create-project kingscode/laravel-api-starter --stability=dev --prefer-source
```

## Installation
Begin by pulling the docker containers and booting docker.
```bash
$ docker-compose up --build -d
```

Then get into the `app` container.
```bash
$ docker exec -it app bash
```

And run the following commands.
```bash
$ composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan migrate
$ php artisan passport:keys
```
