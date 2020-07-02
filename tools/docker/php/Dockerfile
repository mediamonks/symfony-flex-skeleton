FROM amazonlinux:2

ARG php_version=7.2

RUN yum install -y shadow-utils gcc make git zip unzip && \
    useradd -ms /bin/bash -d /var/www www-data && \
    yum -y update && \
    yum install -y amazon-linux-extras

# PHP
RUN amazon-linux-extras enable php${php_version} && \
    yum clean metadata && \
    yum install -y php-mbstring php-gd php-pear php-devel php-fpm

# X-Debug
RUN pear config-set php_ini /etc/php.ini && \
    pecl install xdebug && \
    echo 'xdebug.max_nesting_level=250' >> /etc/php.ini && \
    echo 'xdebug.remote_enable=1' >> /etc/php.ini && \
    echo 'xdebug.remote_connect_back=1' >> /etc/php.ini & \
    echo 'xdebug.idekey=PHPSTORM' >> /etc/php.ini

# FPM
COPY ./www.conf /etc/php-fpm.d/www.conf

# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer

EXPOSE 9005

CMD ["/usr/sbin/php-fpm --nodaemonize"]