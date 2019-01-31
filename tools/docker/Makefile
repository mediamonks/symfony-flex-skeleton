qa: lint analyse test

#frontend:
#    @echo "=================================================================="
#    @docker run --volume $PWD/../../source/frontend:/frontend --workdir /frontend --rm node:latest bash -c "yarn && yarn build --versionNumber=src && cp -r $PWD/../source/frontend/dist/version/src $PWD/../../source/symfony/public/version/src"

#migrate:
#    @echo "=================================================================="
#    @docker exec php bin/console doctrine:migrations:migrate

lint:
	@echo "=================================================================="
	@docker exec php bash -c "vendor/bin/php-cs-fixer fix"

analyse:
	@echo "=================================================================="
	@docker exec php bash -c "vendor/bin/phpstan analyse -c .phpstan.neon.dist"
	@echo "=================================================================="
	@docker exec php bash -c "vendor/bin/phpmetrics --report-html=./reports/metrics --exclude='vendor,tests,var,bin' ./"
	@echo "=================================================================="
	@docker exec php bash -c "bin/console security:check"

test:
	@echo "=================================================================="
	@docker exec php bash -c "bin/phpunit --coverage-html ./reports/coverage"
