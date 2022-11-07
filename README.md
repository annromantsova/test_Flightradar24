# Test on Symfony


Implement a minimal and useful API for an airplane ticket management system. This management system is a service used by 3rd party (external) systems.

One airplane ticket should contain flight departure time, source and destination airport as well as passenger seat which should be a random number (between 1 and 32) per flight and passenger's passport ID.

### The API should support following operations:
- Create new Ticket
- Cancel Ticket

### Bonus task:
Implement this additional API operation
- Change Seat for Ticket

## Installation

Copy repository.

```sh
git clone https://github.com/annromantsova/test_symfony.git
```

Install and run docker container

```sh
cd test_symfony
cd docker
docker-compose -f ./docker/docker-compose.yml --env-file ./docker/.env up --build
```
After containers are runing we can create and flush DB
*please make shure you run it in docker folder*
```sh
docker compose exec php-fpm bash
# We will run next commands in php-fpm container
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
# Fill the database with dummy data
php bin/console doctrine:fixtures:load
```
Now we have a working server on
http://127.0.0.1:8080

For see all API endpoints we use
http://127.0.0.1:8080/api
