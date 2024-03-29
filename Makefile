#!/usr/bin/make

user_id := $(shell id -u)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null) --file "docker/docker-compose.yml"
php_container_bin := $(docker_compose_bin) run --rm -u "$(user_id)" "php"
php_composer_script := $(php_container_bin) composer run-script

.DEFAULT_GOAL := build

# --- [ Development tasks ] -------------------------------------------------------------------------------------------

build: ## Build container and install composer libs
	$(docker_compose_bin) build --force-rm

install: ## Install all data
	$(php_container_bin) composer update

shell: ## Runs shell in container
	$(php_container_bin) bash

fixer: ## Run fixer to fix code style
	$(php_composer_script) fixer

linter: ## Run linter to check project
	$(php_composer_script) linter

test: ## Run tests
	$(php_composer_script) test

coverage: ## Run tests with coverage
	$(php_composer_script) coverage

generate-entities: ## Generate list of entites from swagger file
	$(php_composer_script) generate-entities
