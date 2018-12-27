all: docker_scripts	ssl	composer permissions environment

docker_scripts:
	@chmod +x *.sh

ssl:
	@./generateSSL.sh

composer:
	@cd /var/www/source/symfony; composer install

permissions:
	@cd /var/www/source/symfony; chmod -R 777 var/log var/cache

environment:
	@./environment.sh