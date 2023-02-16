isDocker := $(shell docker info > /dev/null 2>&1 && echo 1)
isProd := $(shell grep "APP_ENV=prod" .env.local > /dev/null && echo 1)
domain := "lab-sodepac.skyreka.com"
server := "debian@lab-sodepac.skyreka.com"
user := $(shell id -u)
group := $(shell id -g)

sy := php bin/console
node :=
php :=
ifeq ($(isDocker), 1)
	ifneq ($(isProd), 1)
		dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose
		dcimport := USER_ID=$(user) GROUP_ID=$(group) docker-compose -f docker-compose.import.yml
		de := docker-compose exec
		dr := $(dc) run --rm
		sy := $(de) php bin/console
		node := $(dr) node
		php := $(dr) --no-deps php
	endif
endif

.DEFAULT_GOAL := help
.PHONY: help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: deploy
deploy: ## Déploie une nouvelle version du site
	ssh -A $(server) 'cd $(domain) && git pull origin master && make install'

.PHONY: dump
dump:
	$(de) db sh -c 'exec mysqldump -ulms-sodepac -plms-sodepac lms-sodepac > /var/www/var/dump/dump.sql'

.PHONY: dumpimport
dumpimport: ## Import un dump SQL
	$(de) db sh -c 'mysql -ulms-sodepac -plms-sodepac lms-sodepac < /var/www/var/dump/dump.sql'

.PHONY: install
install: vendor/autoload.php ## Installe les différentes dépendances
	APP_ENV=prod APP_DEBUG=0 $(php) composer install --no-dev --optimize-autoloader
	make migrate
	APP_ENV=prod APP_DEBUG=0 $(sy) cache:clear
	$(sy) cache:pool:clear cache.global_clearer
	$(sy) messenger:stop-workers

.PHONY: build-docker
build-docker:
	$(dc) pull --ignore-pull-failures
	$(dc) build php
	$(dc) build messenger
	$(dc) build node

.PHONY: dev
dev: vendor/autoload.php ## Lance le serveur de développement
	$(dc) up

.PHONY: migration
migration: vendor/autoload.php ## Génère les migrations
	$(sy) make:migration

.PHONY: migrate
migrate: vendor/autoload.php ## Migre la base de données (docker-compose up doit être lancé)
	$(sy) doctrine:migrations:migrate -q

.PHONY: rollback
rollback:
	$(sy) doctrine:migration:migrate prev

.PHONY: security-check
security-check: vendor/autoload.php ## Check pour les vulnérabilités des dependencies
	$(de) php local-php-security-checker --path=/var/www

# -----------------------------------
# Déploiement
# -----------------------------------
.PHONY: provision
provision: ## Configure la machine distante
	ansible-playbook -i tools/ansible/hosts.yml tools/ansible/install.yml -v

# -----------------------------------
# Dépendances
# -----------------------------------
vendor/autoload.php: composer.lock
	$(php) composer install
	touch vendor/autoload.php

composer.lock: composer.json
	$(php) composer install

var/dump:
	mkdir var/dump

public/assets/manifest.json: package.json
	$(node) yarn
	$(node) yarn run build
