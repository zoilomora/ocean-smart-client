UID=$(shell id -u)
GID=$(shell id -g)
DOCKER_PHP_SERVICE=php

.PHONY: start
start: erase cache-folders build composer-install bash

.PHONY: erase
erase:
		docker-compose down -v

.PHONY: build
build:
		docker-compose build && \
		docker-compose pull

.PHONY: cache-folders
cache-folders:
		mkdir -p ~/.composer && chown ${UID}:${GID} ~/.composer

.PHONY: composer-install
composer-install:
		docker-compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} composer install

.PHONY: stop
stop:
		docker-compose stop

.PHONY: bash
bash:
		docker-compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} sh

.PHONY: logs
logs:
		docker-compose logs -f ${DOCKER_PHP_SERVICE}
