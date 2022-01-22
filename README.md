## Nauni Service / API template

### Requirements
```
docker >= v20
docker-compose >= v1.27 
```

### Basic usage

#### Get your own version
- Fork the template repository on Github
- Set up a new project repository using the template as the base
- SIn this example we will use "training-api"

#### Clone the new project
```sh
git clone git@github.com:nauni-limited/training-api.git
```

### Rename the service in the docker-compose files
- Replace "service-" to you liking. In this example we will use "training-api-"

### Use Xdebug and change exposed ports
- Rename docker-compose.override.yml and change it as you like
- Using pcov is faster, only use xdebug when you are debugging code

#### Run the project for the first time
```sh
docker-compose pull
docker-compose build --no-cache
docker-compose up
docker exec -ti training-api-fpm composer install
```

#### Run the migrations
```sh
# Run for dev env
docker exec -ti training-api-fpm console doctrine:migrations:migrate
# Run for tests
docker exec -ti training-api-fpm console doctrine:migrations:migrate --env=test --no-interaction
```
You will need to repeat this if you rebuild the project or if you kill / remove your containers.


#### Run individual tests for the whole project
```sh
#### PHP Unit
# all phpunit tests
docker exec -ti training-api-fpm phpunit tests
# feature tests only
docker exec -ti training-api-fpm phpunit tests/Feature
# generate html code coverage 
docker exec -ti training-api-fpm phpunit tests --coverage-html var/coverage

#### PHP CS
# run checks
docker exec -ti training-api-fpm vendor/bin/phpcs src tests && echo "PASS"
#auto fix code style where possible
docker exec -ti training-api-fpm vendor/bin/phpcbf src tests

#### PHP STAN
docker exec -ti training-api-fpm vendor/bin/phpstan analyse \
              --level=max \
              --configuration=phpstan-src.neon \
              src

docker exec -ti training-api-fpm vendor/bin/phpstan analyse \
              --level=max \
              --configuration=phpstan-tests.neon \
              tests
```

#### Run all tests for a suite
```sh
docker exec -ti training-api-fpm console test:all [suite]
```

#### Rebuild the project images
```sh
docker-compose kill \
  && docker-compose rm  -f \
  && docker-compose build --no-cache \
  && docker-compose up --remove-orphans 
```