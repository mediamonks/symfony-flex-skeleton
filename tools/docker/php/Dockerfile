ARG PHP_VERSION=8.1.6

FROM php:${PHP_VERSION}-fpm-alpine

ARG with_xdebug="true"

# Packages
RUN apk add --update --no-cache git zip unzip bash build-base autoconf linux-headers

RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN apk --no-cache add shadow && usermod -u 1000 www-data

# User & Permissions
RUN mkdir -p /var/log/php-fpm /run/php-fpm \
    && chown -R www-data:www-data /var/www /var/log/php-fpm/ /run/php-fpm/

# X-Debug
RUN if [ "$with_xdebug" = "true" ]; then \
    pear config-set php_ini /etc/php.ini \
    && pecl install xdebug \
    && echo 'xdebug.mode=debug' >> /etc/php.ini \
    && echo 'xdebug.client_host=host.docker.internal' >> /etc/php.ini \
    && echo 'xdebug.max_nesting_level=250' >> /etc/php.ini \
    && echo 'xdebug.remote_enable=1' >> /etc/php.ini \
    && echo 'xdebug.remote_connect_back=1' >> /etc/php.ini \
    && echo 'xdebug.idekey=PHPSTORM' >> /etc/php.ini ; \
    fi

# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
   && php composer-setup.php --install-dir=/usr/bin --filename=composer

# Symfony cli
RUN curl -s https://get.symfony.com/cli/installer | bash \
    &&  mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# FPM
COPY ./www.conf /usr/local/etc/php-fpm.d/www.conf

# Custom php config
COPY ./custom/* /etc/php.d/

USER www-data

CMD ["php-fpm --nodaemonize"]