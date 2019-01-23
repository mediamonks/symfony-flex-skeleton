frontend:
	@echo "Building frontend"
	docker run --volume $PWD/../../source/frontend:/frontend --workdir /frontend --rm node:latest bash -c "yarn && yarn build --versionNumber=src && cp -r $PWD/../source/frontend/dist/version/src $PWD/../../source/symfony/public/src"

migrate:
	docker exec php bin/console doctrine:migrations:migrate

qa: linting analyse test
	 
	

preview:
	@echo "Building preview"

linting:
	@echo "||"
	@echo "|| Code linting with --dry-run"
	@echo "=================================================================="
	@echo ""
	docker exec php bash -c "vendor/bin/php-cs-fixer fix --dry-run"

analyse:
	@echo ""
	@echo "||"
	@echo "|| Static analysis"
	@echo "=================================================================="
	@echo ""
	docker exec php bash -c "vendor/bin/phpstan analyse -c .phpstan.neon.dist"

test: phpunit

phpunit:
	@echo ""
	@echo "||"
	@echo "|| Running php-unit"
	@echo "=================================================================="
	@echo ""
	docker exec php bash -c "bin/phpunit --coverage-html ./reports/coverage"
	docker exec php bash -c "vendor/bin/phpmetrics --report-html=./reporting/metrics ./"
