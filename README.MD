# Symfony Skeleton
[Required Software](#recuired-software)  
[Configuration](#configuration)  
[Tools](#tools)  
[Recipes](#recipes)  

## Required software
[Docker](https://www.docker.com/) or [Vagrant](https://www.vagrantup.com/).  

## Installation
    composer create-project mediamonks/skeleton-symfony:~9.1 . --repository-url=https://toran.mediamonks.net/repo/private/  

## Configuration
The create-project command runs the skeleton installer.   
Run docker-compose up or vagrant up when done.  

To install packages run composer install from within the container in `/var/www/html/source/symfony`.  
This ensures the correct versions of a package will be installed.  

List of useful recipes/packages can be found [here](#recipes).
    
## Tools
Tools included in this skeleton:  
- phpunit (https://phpunit.de/)  
- phpstan (https://github.com/phpstan/phpstan)  
- php-cs-fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)  
- php metrics (https://www.phpmetrics.org/)  

All above tools are bundled in a make file. To run it, use below command in the `./tools/docker` folder:  
    
    make qa
    
This command runs all the above tools and outputs 2 reports (phpmetrics and codecoverage) in: ./source/symfony/reports.  
PHP Metrics: `./source/symfony/reports/metrics/index.html`  
CodeCoverage: `./source/symfony/reports/coverage/index.html`  

    
### PHP Metrics
**The bubbles chart.**  

Each file is symbolized by a circle.  
Size of the circle represents the [Cyclomatic complexity](https://en.wikipedia.org/wiki/Cyclomatic_complexity).  
Color of the circle represents the Maintainability index.  
Hover over circle for more details.  

**The Table chart.**  
Shows the main metrics of each package.  
Check (http://www.phpmetrics.org/documentation/index.html) for the meaning of the abbrevations.  


## Recipes
- Admin (Installs Sonata Admin)  
- Api (MM rest api bundle)  
- Pii (Doctrine Extensions for encryption and hashing)  
- Media Bundle (mediamonks/sonata-media-bundle)  

Other recipes can be found on (https://flex.symfony.com/)