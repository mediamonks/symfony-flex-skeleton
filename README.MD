# __project_name__
| Hostname        | Vagrant IP           | PHP Version  |
| ------------- |:-------------:| -----:|
| __hostname__      | __vagrant_ip__ | __php_version__ |


# Symfony Skeleton
[Required Software](#recuired-software)  
[Usage](#usage)  
[PHP Metrics](#php-metrics)  
[Recipes](#recipes)

## Required software
[Docker](https://www.docker.com/) or [Vagrant](https://www.vagrantup.com/).

## Usage
### PHP Container
In case of the following, always run the commands inside the PHP container:
- Installing new Composer packages
- Running tests
- Remove cache files

### Testing and Linting
Tools included in this skeleton:  
- phpunit (https://phpunit.de/)  
- phpstan (https://github.com/phpstan/phpstan)  
- php-cs-fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)  
- php metrics (https://www.phpmetrics.org/)  

All above tools are bundled in one Makefile. To run it, use the following command in the `/tools/docker` folder:  
    
    make qa
    
This command runs all the above tools and outputs 2 reports:  
- PHP Metrics in: `/source/symfony/reports/metrics/index.html`  
- CodeCoverage in: `/source/symfony/reports/coverage/index.html`  

    
#### PHP Metrics
**The bubbles chart.**  
Each file is symbolized by a circle.  
Size of the circle represents the [Cyclomatic complexity](https://en.wikipedia.org/wiki/Cyclomatic_complexity).  
Color of the circle represents the Maintainability index.  
Hover over circle for more details.  

**The Table chart.**  
Shows the main metrics of each package.  
Check http://www.phpmetrics.org/documentation/index.html for the meaning of the abbrevations.  


## Recipes
- admin (Sonata Admin with user management)  
- api (MediaMonks Rest API Bundle)  
- pii (Doctrine Extensions for encryption and hashing)  
- sonata-media (MediaMonks Sonata Media Bundle)  

Private recipes can be found here: https://symfony.mediamonks.net
Public recipes can be found here: https://flex.symfony.com