# Symfony skeleton

## Features:
- Symfony 5.4(LTS) and 6.*
- Docker setup, supports php 7.4 and 8
- [PHPStan](https://phpstan.org/)

## Installation:
```bash
composer create-project mediamonks/skeleton-symfony:~10.0.0 . --repository-url=https://satis.monks.tools
```  
Via Docker:   
```bash
docker run --rm --interactive --tty --user "$(id -u):$(id -g)" --volume $PWD:/app --volume ~/.ssh:/root/.ssh composer create-project mediamonks/skeleton-symfony:~10.0.0 .
```

## Available commands:
- `composer analyse`, runs PHPStan
- `composer security-check`, runs symfony's security checker (via symfony-cli)

## Customizing PHP:  
You can customize php by adding ini files to `tools/docker/php/custom`.  

### Increasing php memory limit
- Add `memory_limit.ini` to `tools/docker/php/custom`.  
- Add the following content: 
  ```ini
  memory_limit=256M
  ```
- Run `docker-compose up --build --force-recreate`

### Increase file upload size
- Add `file_uploads.ini` to `tools/docker/php/custom`.
- Add the following content:   
  ```ini
  file_uploads=On
  upload_max_filesize=10M
  ```
- Run `docker-compose up --build --force-recreate`

## Recommended packages
- Working with filesystems: https://github.com/thephpleague/flysystem-bundle
- Command bus pattern: https://github.com/thephpleague/tactician-bundle