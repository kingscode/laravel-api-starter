# Laravel Api Starter

[![Build Status](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/badges/build.png?b=master)](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kingscode/laravel-api-starter/?branch=master)

Our base `laravel/laravel` installation for `vue` front-end applications.

## Usage
```bash
composer create-project kingscode/laravel-api-starter
```

## Installation

```bash
composer install
```

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

```bash
php artisan migrate
```

```bash
php artisan db:seed
```

```bash
php artisan passport:install
```

