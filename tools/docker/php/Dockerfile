FROM amazonlinux:1

RUN yum -y groupinstall "Development Tools"
RUN useradd -ms /bin/bash -d /var/www www-data

RUN yum -y update
RUN yum install -y php71 php71-fpm php71-mbstring php71-pdo

COPY ./www.conf /etc/php-fpm-7.1.d/www.conf
COPY ./xdebug-php71.so /usr/local/php/xdebug.so

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/bin --filename=composer

RUN echo 'zend_extension="/usr/local/php/xdebug.so"' >> /etc/php.ini
RUN echo 'xdebug.max_nesting_level=250' >> /etc/php.ini
RUN echo 'xdebug.remote_enable=1' >> /etc/php.ini
RUN echo 'xdebug.remote_connect_back=1' >> /etc/php.ini
RUN echo 'xdebug.idekey=PHPSTORM' >> /etc/php.ini

EXPOSE 9005

CMD ["/usr/sbin/php-fpm --nodaemonize"]