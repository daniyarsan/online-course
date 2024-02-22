#!/usr/bin/make -f
# SHELL = /bin/sh
APP_DIR=$(shell echo $$(cd . && pwd))
DC=docker-compose

first_install: start composer

########## COMBINED COMMANDS
start: build up
restart: stop up
rebuild: stop start

########## STEPS
build:
	cd $(APP_DIR) && $(DC) build
up:
	docker rm -f $$(docker ps -a | grep quest | awk '{print $$1}') || echo
	cd $(APP_DIR) && $(DC) up -d --remove-orphans --force-recreate
	$(MAKE) composer
down:
	cd $(APP_DIR) && $(DC) down -v --remove-orphans
stop:
	cd $(APP_DIR) && $(DC) stop
composer:
	cd $(APP_DIR) && $(DC) exec -T api composer install && \
	wait
comp:
	cd $(APP_DIR) && $(DC) exec -T api composer


####################################################################

clear:
	$(DC) down -v --remove-orphans
	docker container prune -f
	docker image prune -f
	docker volume prune -f
	@echo "======================="