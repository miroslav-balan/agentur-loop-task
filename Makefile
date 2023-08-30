.PHONY: dev
BLUE="\033[1;34m"
END_BLUE="\033[0m"
GREEN="\033[1;32m"
END_GREEN="\033[0m"

include .env

dev: prepare_infrastructure prepare_app_dev clear_cache fix_files_owners_and_permissions ready
install: prepare_infrastructure prepare_app_dev prepare_app_conf clear_cache fix_files_owners_and_permissions ready

prepare_infrastructure:
	@echo ${BLUE}"Prepare Dockerized Infrastructure..."${END_BLUE}
	docker compose stop
	docker compose pull
	docker compose up -d
	@echo ${BLUE}"Prepare Dockerized Infrastructure - OK"${END_BLUE}

prepare_app_dev:
	@echo ${BLUE}"Prepare App..."${END_BLUE}
	docker compose exec php-fpm composer install
	docker compose exec php-fpm php artisan migrate
	docker compose exec php-fpm php artisan actions
	@echo ${BLUE}"Prepare App - OK"${END_BLUE}

prepare_app:
	@echo ${BLUE}"Prepare App..."${END_BLUE}
	docker compose exec php-fpm composer install
	@echo ${BLUE}"Prepare App - OK"${END_BLUE}

prepare_app_conf:
	@echo ${BLUE}"Prepare App Configuration..."${END_BLUE}
	cp .env.docker .env
	@echo ${BLUE}"Prepare App Configuration - OK"${END_BLUE}

fix_codestyle:
	@echo ${BLUE}"Fixing PHP Codestyle..."${END_BLUE}
	docker compose exec php-fpm php -c ./docker/php-fpm/disable-xdebug.ini vendor/bin/pint
	@echo ${BLUE}"Fixing PHP Codestyle - OK"${END_BLUE}

check_codestyle:
	@echo ${BLUE}"Checking PHP Codestyle..."${END_BLUE}
	docker compose exec php-fpm php -c ./docker/php-fpm/disable-xdebug.ini vendor/squizlabs/php_codesniffer/bin/phpcs --colors
	@echo ${BLUE}"Checking PHP Codestyle - OK"${END_BLUE}
	@echo ${BLUE}"Checking JS Codestyle..."${END_BLUE}
	docker compose run node bash -c "npm i && npm run cs-check"
	@echo ${BLUE}"Checking JS Codestyle - OK"${END_BLUE}

code_coverage_report:
	@echo ${BLUE}"Creating code coverage report..."${END_BLUE}
	docker compose exec php-fpm php -d xdebug.coverage_enable=1 ./vendor/phpunit/phpunit/phpunit --coverage-html ./storage/build/coverage-report --configuration ./phpunit.xml --testsuite Feature,Unit --stop-on-failure -d memory_limit=2048M
	@echo ${BLUE}"Creating code coverage report - OK"${END_BLUE}

clear_cache:
	@echo ${BLUE}"Clearing the cache..."${END_BLUE}
	docker compose exec php-fpm php -c ./docker/php-fpm/disable-xdebug.ini artisan cache:clear
	docker compose exec php-fpm composer dumpautoload
	@echo ${BLUE}"Clearing the cache - OK"${END_BLUE}

stop:
	@echo ${BLUE}"Stopping..."${END_BLUE}
	docker compose stop
	@echo ${GREEN}" "${END_GREEN}
	@echo ${GREEN}"====================================="${END_GREEN}
	@echo ${GREEN}"=      agentur-loop dev task is stopped. Bye!     ="${END_GREEN}
	@echo ${GREEN}"====================================="${END_GREEN}
	@echo ${GREEN}" "${END_GREEN}

fix_files_owners_and_permissions:
	@echo ${BLUE}"Fixing files owners and permissions..."${END_BLUE}
	docker compose exec php-fpm chown -R $(shell id -u):$(shell id -g) *.*
	docker compose exec php-fpm chown -R $(shell id -u):$(shell id -g) /application/storage/logs
	docker compose exec php-fpm chown -R $(shell id -u):$(shell id -g) /application/database
	docker compose exec php-fpm chown -R $(shell id -u):$(shell id -g) /application/vendor
	@echo ${BLUE}"Fixing files owners and permissions - OK"${END_BLUE}

ssl_renew:
	@echo ${BLUE}"Renew your certificates..."${END_BLUE}
	openssl req -x509 -newkey rsa:4096 -sha256 -days 3650 -nodes \
	  -keyout docker/nginx/ssl/nginx.key -out docker/nginx/ssl/nginx.crt -subj "/C=AT/ST=AT/L=Vienna/O=agentur-loop/OU=IT/CN=agentur-loop-task.local" \
	  -addext "subjectAltName=DNS:agentur-loop-task.local,DNS:www.agentur-loop-task.local,IP:10.0.0.1"
	@echo ${BLUE}"Renew your certificates - OK!"${END_BLUE}


test:
	@echo ${BLUE}"Testing..."${END_BLUE}
	@echo ${BLUE}"Running Unit tests..."${END_BLUE}
	docker compose exec php-fpm php -c ./docker/php-fpm/disable-xdebug.ini ./vendor/phpunit/phpunit/phpunit --configuration ./phpunit.xml --testsuite Unit
	@echo ${BLUE}"Unit tests - done"${END_BLUE}
	@echo ${BLUE}"Running Functional tests..."${END_BLUE}
	docker compose exec php-fpm php -c ./docker/php-fpm/disable-xdebug.ini ./vendor/phpunit/phpunit/phpunit --configuration ./phpunit.xml --testsuite Feature

ready:
	@echo ${GREEN}" "${END_GREEN}
	@echo ${GREEN}"====================================="${END_GREEN}
	@echo ${GREEN}"=      agentur-loop dev task is ready. Enjoy!     ="${END_GREEN}
	@echo ${GREEN}"====================================="${END_GREEN}
	@echo ${GREEN}" "${END_GREEN}
