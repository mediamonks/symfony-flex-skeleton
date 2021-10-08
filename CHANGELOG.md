## 10.0.0 draft
- Support: Symfony 5.3, 5.4 (upcoming lts) and 6.0  
- Support: Docker, PHP 7.4 and 8 as build argument (`php_version`, default 7.3)  
- Support: Docker, Libsodium installation as build argument (`with_libsodium`, default false)  
- Added: Symfony's updated security-checker (`composer security-check`)  
- Added: PHPStan and defaults (`composer analyse`)
- Updated: Docker, images to amazonlinux:2  
- Updated: Docker, control installation of php_version, Libsodium and Xdebug with build args:  
  - `php_version`, default 7.4  
  - `with_libsodium`, default false  
  - `with_xdebug`, default true  
- Fixed: Files created by docker are now also owned by the host user, chown -R is therefore not needed anymore.      
- Removed: Vagrant setup  
- Removed: PHP-cs-fixer, phpmetrics  
- Removed: Makefile in Docker setup

## 9.1.0 (2018-21-27)
- Improvement: add makefile for docker scripts
- Improvement: change Symfony default environments to MM hosting environments
- Improvement: replace APP_ENV=dev to APP_ENV=local in .env

## 9.0.4 (2018-11-28)
- Improvement: Temp files permission fix for www-data (nginx) + load all php files in public directory, not only index.php

## 9.0.3
- Improvement: Add php71-mysqli php71-gd to php container (Docker)

## 9.0.2 (2018-11-13)
- Improvement: Don't clear ENV vars in php-fpm at startup

## 9.0.1 (2018-11-12)
- Bugfix: Fixed replace values in Skeleton Installer

## 9.0.0 (2018-11-12)
- Improvement: Docker setup based on amazonlinux:1 image

## 8.0.2 (2018-10-30)
- Improvement: Remove unnecessary loop and OSX check in Vagrantfile

## 8.0.1 (2018-10-30)
- Improvement: Only show warning for vagrant-triggers plugin when it's installed

## 8.0.0 (2018-10-30)
- Improvement: Update to Symfony + Skeleton ^4.0 and remove php 7.0 support in Skeleton installer

## 7.0.0 (2018-10-30)
- Improvement: Update Vagrantfile based on included triggers in Vagrant 2.2.*

## 6.0.9 (2018-04)
- Improvement: Add docker shortcut .sh scripts

## 6.0.0 (2018-02)
- Based on Symfony Flex

## 5.2.15 (2017-08-25)
- Bugfix: Change timeout for Process in Skeleton installer
- Improvement: Change 'Authorization' header for ApiBundle to 'Token'

## 5.2.14 (2017-07-25)
- Bugfix: only ignore composer.lock in root folder of project

## 5.2.13 (2017-07-25)
- Improvement: Also delete vendor folder after composer create-project

## 5.2.12 (2017-07-13)
- Bugfix: Fixed static hostname

## 5.2.11 (2017-07-13)
- Improvement: Only generate SSL certificate if the certificate does not exists

## 5.2.10 (2017-07-11)
- Improvement: Updated Skeleton dependencies

## 5.2.7 - 5.2.9 (2017-07-11)
- Improvement: Ajusting the way the installer installs dependencies

## 5.2.6 (2017-07-11)
- Improvement: Changed local domain to .lcl instead of .local

## 5.2.5 (2017-07-11)
- Feature: Added https support!

## 5.2.4 (2017-07-11)
- Improvement: Skeleton Installer upgrade

## 5.2.3 (2017-07-10)
- Bugfix: Hostnames Vagrant fix

## 5.2.2 (2017-07-10)
- Improvement: Add .dev hostname for OSX

## 5.2.1 (2017-07-10)
- Feature: Multiple Vagrant hostnames
- Improvement: Database table filter for sessions
- Improvement: Removed log config for old Vagrant box

## 5.2.0 (2017-06-16)
- Bugfix: __toString() User entity

## 5.1.10 (2017-06-16)
- Improvement: Vagrant triggers always after action
- Improvement: Lowercase string normalization in Skeleton Installer

## 5.1.9 (2017-06-16)
- Improvement: Update composer dependencies

## 5.1.8 (2017-06-14)
- Improvement: Check if vagrant-triggers plugin is installed

## 5.1.7 (2017-06-14)
- Improvement: Ask for Administrator/sudo password before editing hostfile on OSX, Linux and OSX (no hostmanager/updater plugin needed anymore)

## 5.1.6 (2017-06-13)
- Improvement: Assets install with Symlinks

## 5.1.5 (2017-06-13)
- Improvement: Installer: random_int() -> rand()

## 5.1.4 (2017-06-12)
- Improvement: Update to new Vagrant base box: 'mediamonks/linux-docker'

## 5.1.3 (2017-06-08)
- Improvement: Docker-compose version 3

## 5.1.2 (2017-06-08)
- Improvement: Nicer ascii for installer

## 5.1.1 (2017-06-08)
- Improvement: Save commit hash while installing Skeleton

## 5.1.0 (2017-06-08)
- Feature: PHP Version 7.1 for Docker
- Improvement: Add new default values for Skeleton installer

## 5.0.3 (2017-06-07)
- Improvement: Add newline in installation after rendering the user table

## 5.0.2 (2017-06-07)
- Improvement: Add back-ticks around username and passwords in installer markdown code

## 5.0.1 (2017-06-07)
- Improvement: Small improvements Skeleton installer

## 5.0.0 (2017-06-07)
- Feature: Upgrade to Symfony 3.3
- Improvement: Refactor Skeleton installation and directory structure

## 4.0.6 (2017-05-22)
- Improvement: Cleanup Dockerfile (php-extensions)

## 4.0.5 (2017-05-19)
- Improvement: Updated hostupdate-down.sh script for Mac and Windows

## 4.0.4 (2017-05-18)
- Feature: Vagrant triggers for update host files
- Improvement: Linux line separators
- Improvement: Fallback for Vagrant config file

## 4.0.3 (2017-05-18)
- Feature: Added default phpunit.xml.dist
- Improvement: Allow disabling IP whitelist for admin
- Improvement: Increase default cache on homepage to 1 hour
- Improvement: Clear cache headers previously set by webserver

## 4.0.2 (2017-05-17)
- Improvement: Added .vagrant and .idea to .gitignore in installer script

## 4.0.1 (2017-05-17)
- Improvement: Updated composer dependencies

## 4.0.0 (2017-05-16)
- Improvement: Changed to the new directory structure
- Feature: Added Vagrant and Docker setup files
- Bugfix: Styling on login page Sonata

## 3.1.0 (2017-02-13)
- Improvement: Upgraded to MediaMonksRestApiBundle 3.0
- Improvement: Updated all minor dependencies
- Bugfix: Removed edit link from admin to prevent routing issues

## 3.0.0 (2017-01-12)
- Feature: Upgrade to Symfony 3.2
- Feature: Removed FOS User Bundle in favor of our own user management
- Feature: Removed Symfony internal caching as this is no longer useful for PHP 7+

## 2.2.0 (2017-01-12)
- Improvement: Using new environment detection script

## 2.1.1 (2016-12-23)
- Bugfix: Fixes framework cache configuration

## 2.1.0 (2016-12-22)
- Feature: Uses utf8mb4 instead of utf8

## 2.0.0 (2016-12-19)
- Improvement: Requires PHP 5.6 or PHP 7
- Improvement: Using new environment naming scheme (local, development, testing, acceptance, production)
- Improvement: Updated dependencies to latest versions

## 1.0.0 (2016-12-16)
- Released 0.6.3 as 1.0.0

## 0.6.3 (2016-09-02)

- Bugfix: environment check in console is now using "ENVIRONMENT" instead of "SYMFONY_ENV" so it's compatible with MediaMonks Hosting

## 0.6.2 (2016-07-05)

- Improvement: Using role based access in Sonata by default
- Improvement: Forcing to go to admin dashboard after logging in to admin

## 0.6.1 (2016-06-01)

- Improvement: Changed api endpoints /api/security to /api/auth
- Bugfix: JWTs are now signed with Symfony secret

## 0.6.0 (2016-05-31)

- Feature: Added authentication to the AppApiBundle which uses /api/security/login and /api/security/logout with JWT
- Improvement: A database connection for sessions is now only made if a session is actually used
- Improvement: Upgraded dependencies to latest stable versions

## 0.5.0 (2016-05-23)

- Improvement: Upgraded Sonata packages to 3.x branch
- Bugfix: Back tick no longer used for password generation as this could break the installer when outputting the back tick character

## 0.4.3 (2016-05-09)

- Bugfix: Using CommandEvent instead of Event in Composer script for installing Symfony assets as Symfony still uses the deprecated names

## 0.4.2 (2016-05-03)

- Improvement: Disabled non-used SonataUserBundle routes
- Bugfix: Fixed DI extensions for AppApiBundle and AppFrontEndBundle to it's pointing towards the correct services.yml files
- Bugfix: Removed Response folder from AppApiBundle, use reponses from the MediaMonksRestApiBundle instead

## 0.4.1 (2016-04-13)

- Feature: Added support for Behat
- Improvement: Updated rewrite rules in .htaccess
- Improvement: Added a .htaccess file in public bundles dir which prevents 404's
- Bugfix: Fixed replacement of admin template name
- Bugfix: Fixes issue with user groups in admin

## 0.4.0 (2016-04-07)

- Feature: Added support for PHPUnit with Mockery (put your tests in src/ according to Symfony 3 structure)
- Improvement: Upgraded to latest stable versions
- Improvement: Using stable versions for MediaMonks packages

## 0.3.1 (2016-03-02)

- Bugfix: Installer was not creating session table

## 0.3.0 (2016-03-02)

- Feature: Added Sonata Admin
- Feature: emailCanonical is now using hash transformer in User entity
- Feature: Sessions are stored in database by default

## 0.2.0 (2016-02-29)

- Feature: Added mediamonks/doctrine-extensions package
- Feature: Using encryption on email field in User entity

## 0.1.1 (2016-02-25)

- Bugfix: Installer now works in OSX

## 0.1.0 (2016-02-25)

- Feature: Added Symfony 2.8.2 as base
- Feature: Converted to Symfony 3 directory structure
- Feature: Using "htdocs" instead of "web" to comply with MediaMonks Hosting standards
- Feature: environment detection added
- Feature: loading config and parameters based on environment
- Feature: DoctrineMigrationsBundle added
- Feature: StofDoctrineExtensionsBundle added
- Feature: SonataUserBundle/FOSUserBundle added
- Feature: MediaMonksRestApiBundle added
- Feature: Added App Bundles for core, api and front end
- Feature: Added interactive installer