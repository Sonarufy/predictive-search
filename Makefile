DOCKER_COMPOSE   = docker-compose
DOCKER           = docker
DOCKER_PREFIX    = fm_search

DOCKER_PS_A      = $(DOCKER) ps -a
DOCKER_PS_AQ     = $(DOCKER) ps -a -q
DOCKER_PROJ_CONT = $$($(DOCKER_PS_A) |awk '$$NF ~ /$(DOCKER_PREFIX)/ {print $$1}')

EXEC_PHP         = $(DOCKER) exec $(DOCKER_PREFIX)_php-fpm_1 zsh -c

PROJ_DIR         = cd search_engine
CONSOLE_EXEC     = php bin/console

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

build_dev: ## Build docker project | dev
	$(DOCKER_COMPOSE) -p $(DOCKER_PREFIX) build
	$(MAKE) rm
	$(DOCKER_COMPOSE) -p $(DOCKER_PREFIX) up -d

start_dev: ## Start the project | dev
	$(DOCKER_COMPOSE) -p $(DOCKER_PREFIX) up -d --remove-orphans --no-recreate

build_review: ## Build docker project | review
	$(DOCKER_COMPOSE) -p $(DOCKER_PREFIX) build
	$(MAKE) rm
	$(DOCKER_COMPOSE) -f docker-compose-review.yml -p $(DOCKER_PREFIX) up -d

start_review: ## Start the project | review
	$(DOCKER_COMPOSE) -f docker-compose-review.yml -p $(DOCKER_PREFIX) up -d --remove-orphans --no-recreate

stop: ## Stop all docker project containers
	-$(DOCKER) stop $(DOCKER_PROJ_CONT)

restart_dev: ## Restart the project | dev
	$(MAKE) stop
	$(MAKE) start_dev

rm: stop ## Delete all docker project containers
	-$(DOCKER) rm $(DOCKER_PROJ_CONT)

cli: ## Access docker cli
	$(DOCKER) exec -ti $(DOCKER_PREFIX)_php-fpm_1 zsh -c "stty columns `tput cols`; stty rows `tput lines`;exec zsh"

assets_min: ## Install/Update the assets with minify
	$(EXEC_PHP) "$(PROJ_DIR) && npm install && bundle install && npm run prod"

update_db: ## doctrine:schema:update
	$(EXEC_PHP) "$(PROJ_DIR) && $(CONSOLE_EXEC) doctrine:schema:update --force"

chmod: ## Sets cache and log folders rights
	$(EXEC_PHP) "$(PROJ_DIR) && chmod 777 .env"
	$(EXEC_PHP) "$(PROJ_DIR) && chmod 777 -R var/cache"
	$(EXEC_PHP) "$(PROJ_DIR) && chmod 777 -R src"
	$(EXEC_PHP) "$(PROJ_DIR) && chmod 777 -R config"
	$(EXEC_PHP) "$(PROJ_DIR) && chmod 777 -R var/log"
	$(EXEC_PHP) "$(PROJ_DIR) && chmod 777 -R vendor"

clear_cache: ## Clear cache
	$(EXEC_PHP) "$(PROJ_DIR) && $(CONSOLE_EXEC) cache:clear"
	$(MAKE) chmod

vendor: ## Composer install
	$(EXEC_PHP) "$(PROJ_DIR) && composer install --no-interaction"
	$(MAKE) chmod

clear_vendor: ## Delete vendor directory content
	$(EXEC_PHP) "$(PROJ_DIR) && rm -rf vendor/"

install: build_dev vendor assets init_db ## Install project

update: start_dev vendor assets update_db ## Update project

.PHONY: build_dev build_review update
