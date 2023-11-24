all: up

cp-env:
	@test -f .env || cp .env.example .env

build:
	./build.sh

install: cp-env build composer-install up migrate

up:
	@docker-compose up -d --build --remove-orphans

down:
	@docker-compose down

down-v:
	@docker-compose down -v

restart:
	@docker-compose restart

stop:
	@docker-compose stop

env:
	@docker-compose exec --user=www-data php bash

env-root:
	@docker-compose exec php bash

composer-install:
	@docker-compose run --rm -e COMPOSER_MEMORY_LIMIT=-1 php composer install

composer-command:
	@docker-compose run --rm php composer $(command)

migrate:
	@docker-compose exec --user=www-data php bin/console doctrine:migrations:migrate
