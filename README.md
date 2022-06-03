# Symfony skeleton

## Features:
- Symfony 5.4(LTS) and 6.*
- Docker setup, supports php 7.4 and 8
- [PHPStan](https://phpstan.org/)

## Installation:
```bash
composer create-project mediamonks/skeleton-symfony:^10.0 . --repository-url=https://satis.monks.tools
```  
Via Docker:   
```bash
docker run --rm --interactive --tty --user "$(id -u):$(id -g)" --volume $PWD:/app --volume ~/.ssh:/root/.ssh composer create-project mediamonks/skeleton-symfony:^10.0 .
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

## Xdebug
The `php` container is already prepared to run Xdebug.  
However, to actually use it, a few manual configurations are required.

### PhpStorm setup
- While in `PhpStorm` open the setting (Windows shortcut: `Ctrl` + `Alt` + `S`)
- Navigate to `PHP` -> `Servers`
- Click the `+` icon to add a new entry
- Fill the _Name_ and the _Host_ with hostname provided for the project (E.g. `example-project.lcl`)
- Choose `Xdebug` _Debugger_
- Check _Use path mappings_
    - Map the project symfony folder to `/var/www/source/symfony`

### Browser setup
- For `Chrome` install the [Xdebug helper extension](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc)
    - For other browsers, search for alternatives.
- Once the extension is installed, just simply enable it by setting it to `Debug`.