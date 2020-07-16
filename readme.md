# Laravel Api Starter

![PHPUnit](https://github.com/kingscode/laravel-api-starter/workflows/PHPUnit/badge.svg)

Our base `laravel/laravel` installation for `vue` front-end applications.

## Usage
Use this repository as a template repository in github or clone the repository.

After install there will be a default user with the following credentials.
```bash
email: info@kingscode.nl
password: secret
```

## Installation
### With Docker
Docker helps a ton by providing us a unison development environment that allows us to quickly install new dependencies and share the configuration of those.

Begin by pulling the docker containers and booting docker.
```bash
$ docker-compose up --build -d
```

Then get into the `app` container.
```bash
$ docker exec -it app bash
```

Where you'll run the following commands.
```bash
$ cp .env.example .env
$ composer install
$ pa key:generate
$ pa migrate
$ pa db:seed
```

### Without Docker
You can also run without Docker, but you'll have to do the walk of atonement. 

Start by setting up your environment and installing dependencies.

Then run the following to copy the `.env` file and fill it accordingly:
```bash
$ cp .env.example .env
```

Then run the following commands to get it all booted up.
```bash
$ composer install
$ php artisan key:generate
$ php artisan migrate
$ php artisan db:seed
```
