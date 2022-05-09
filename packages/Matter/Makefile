# import config.
cnf ?= .env
include $(cnf)
export $(shell sed 's/=.*//' $(cnf))


# HELP
# This will output the help for each task
# thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: run help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help


DOCKER         := docker
DOCKER_COMPOSE := docker-compose

# Default docker-compose file
#
COMPOSE_FILE_DEFAULT_NAME := docker-compose.yml

# Default container for docker actions
# NOTE: EDIT THIS TO AVOID WARNING/ERROR MESSAGES
#
app := $(shell basename `pwd` | tr '[:upper:]' '[:lower:]')-app-1
mysql := $(shell basename `pwd` | tr '[:upper:]' '[:lower:]')-mysql-1
mariadb := $(shell basename `pwd` | tr '[:upper:]' '[:lower:]')-mariadb-1
#$(echo "-app-1"))

# Shell command for 'shell' target
#
SHELL_CMD := /bin/bash
MYSQL_CMD := "/usr/bin/mysql -u$(DB_USERNAME) -p$(DB_PASSWORD)"

ME := $(realpath $(firstword $(MAKEFILE_LIST)))

# Expected to be parent folder of compose file
# Contains trailing '/'
#
PWD := $(dir $(ME))

container ?= $(CONTAINER_DEFAULT)
file      ?= $(PWD)$(COMPOSE_FILE_DEFAULT_NAME)
service   ?=
services  ?= $(service)

.DEFAULT_GOAL := help

services: ## Lists services
	@$(DOCKER_COMPOSE) -f "$(file)" ps --services

build: ## Builds service images [file|service|services]
	@$(DOCKER_COMPOSE) -f "$(file)" build $(services)

up: ## Starts containers in fresh ( using 'fresh' ) (in detached mode) [file|service|services]
	@$(DOCKER_COMPOSE) -f "$(file)" up --detach $(services)
	@$(DOCKER) exec -it "$(app)" "/usr/bin/composer" "install" "--dev"
	

down: ## Removes containers (preserves images and volumes) [file]
	@$(DOCKER_COMPOSE) -f "$(file)" down
	rm -rf ./vendor

rebuild: down build ## Stops containers (via 'down'), and rebuilds service images (via 'build')

clean: ## Removes containers, images and volumes [file]
	@$(DOCKER_COMPOSE) -f "$(file)" down --volumes --rmi all
	@rm -rf ./vendor

start: ## Starts previously-built containers (see 'build') [file|service|services]
	@$(DOCKER_COMPOSE) -f "$(file)" start $(services)

status: ps ## see 'ps'

reup: rebuild up ## down build ## Stops and rebuilds containers (via 'rebuild'), and starts containers (in detached mode, via 'up' ) [file|service|services]
ps: ## Shows status of containers [file|service|services]
	@$(DOCKER_COMPOSE) -f "$(file)" ps $(services)

log: logs
logs:  ## Shows output of running containers (in 'follow' mode) [file|service|services]
	@$(DOCKER_COMPOSE) -f "$(file)" logs --follow $(services)

stop: ## Stops containers (without removing them) [file|service|services]
	@$(DOCKER_COMPOSE) -f "$(file)" stop $(services)

restart: stop start ## Stops containers (via 'stop'), and starts them again (via 'start')

sh: shell ## see 'shell'
bash:  shell ## see 'shell' (may not actually be bash)
shell:       ## Brings up a shell in default (or specified) container [container]
	@echo "Starting shell ($(SHELL_CMD)) in container '$(app)'"
	@$(DOCKER) exec -it "$(app)" "$(SHELL_CMD)"

# specificly for development here
mysql: ## Brings up a mysql shell in default (or specified) container [container]
	@echo "Starting shell ($(MYSQL_CMD)) in container '$(MYSQL_DEFAULT)'"
	@$(DOCKER) exec -it "$(MYSQL_DEFAULT)" "$(MYSQL_CMD)"
 

# specificly for development here
phpunit: test ## run php unit, see test
test: ## run php unit
	@$(DOCKER) exec -it "$(app)" "./vendor/bin/phpunit"

# proxy commands here.
# all the commands using run args
ifneq (,$(filter $(firstword $(MAKECMDGOALS)),run composer))

  # use the rest as arguments for "run"
  RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(RUN_ARGS):;@:)
endif

run: FORCE
	@$(DOCKER) exec -it "$(app)" $(RUN_ARGS)

composer:
	@$(DOCKER) exec -it "$(app)" "composer" $(RUN_ARGS)

FORCE: