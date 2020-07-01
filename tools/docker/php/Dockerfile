FROM amazonlinux:2

ARG php_version=7.2

RUN yum install -y shadow-utils gcc make
RUN useradd -ms /bin/bash -d /var/www www-data

RUN yum -y update
RUN yum install -y amazon-linux-extras

# PHP
RUN amazon-linux-extras enable php${php_version}
RUN yum clean metadata
RUN yum install -y php-devel php-pecl php-cli php-fpm php-mbstring php-pdo php-mysqli php-gd php-pear

# X-Debug
RUN pear config-set php_ini /etc/php.ini
RUN pecl install xdebug

RUN echo 'xdebug.max_nesting_level=250' >> /etc/php.ini
RUN echo 'xdebug.remote_enable=1' >> /etc/php.ini
RUN echo 'xdebug.remote_connect_back=1' >> /etc/php.ini
RUN echo 'xdebug.idekey=PHPSTORM' >> /etc/php.ini

# FPM
COPY ./www.conf /etc/php-fpm.d/www.conf

# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/bin --filename=composer

EXPOSE 9005

CMD ["/usr/sbin/php-fpm --nodaemonize"]