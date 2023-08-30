## Requirements
Machine with [Docker installed](https://docs.docker.com/engine/install/)

## Instructions

### On a linux machine
copy .env.docker to .env

To install app run
```sh
make install
```

Open url http://122.122.122.11

to run a tests
```sh
make test
```


### On other machines
To install app run

copy .env.docker to .env

```sh
docker compose stop
docker compose pull
docker compose up -d
docker compose exec php-fpm composer install
docker compose exec php-fpm php artisan migrate
docker compose exec php-fpm php artisan actions
```

Open url http://122.122.122.11

to run a tests
```sh
docker compose exec php-fpm php artisan test
```

### Open API contract
Can be found ```resources/docs/api/agentur-loopdev-task-api.yml```
