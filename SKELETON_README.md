# Symfony skeleton

## Features:
- Symfony 5.3, 5.4(dev) and 6(dev)
- Docker setup, supports php 7.3, 7.4 and 8
- [PHPStan](https://phpstan.org/)

## Available commands:
- `composer analyse`, runs PHPStan
- `composer security-check`, runs symfony's security checker (via symfony-cli)

## Customizing PHP:  
You can customize php by adding ini files to `tools/docker/php/custom`.  

### Increasing php memory limit
- Add `memory_limit.ini` to `tools/docker/php/custom`.  
- Add the following content to `memory_limit.ini`: `memory_limit=256M`
- Run `docker-compose up --build --force-recreate`

### Increase file upload size
- Add `file_uploads.ini` to `tools/docker/php/custom`.
- Add the following content to `file_uploads.ini`:   
  ```
  file_uploads=On
  upload_max_filesize=10M
  ```
- Run `docker-compose up --build --force-recreate`