FROM amazonlinux:2

ARG php_version=7.4
ARG with_libsodium="false"
ARG with_xdebug="true"

# Packages
RUN yum -y update \
    && yum install -y shadow-utils gcc make git zip unzip tar iproute mysql amazon-linux-extras \
    && amazon-linux-extras enable php${php_version} \
    && yum clean metadata \
    && yum install -y php-mbstring php-gd php-pear php-devel php-fpm php-pdo php-mysqlnd \
    && yum clean all \
    && rm -rf /var/cache/yum

# User & Permissions
RUN useradd -ms /bin/bash -d /var/www www-data \
    && usermod -u 1000 www-data \
    && mkdir -p /var/log/php-fprm \
    && chown -R www-data:www-data /var/www /var/log/php-fpm/

# Libsodium
RUN if [ "$with_libsodium" = "true" ]; then \
    curl -O https://download.libsodium.org/libsodium/releases/libsodium-1.0.18.tar.gz \
    && tar xfvz libsodium-1.0.18.tar.gz \
    && cd libsodium-1.0.18 \
    && ./configure \
    && make && make install \
    && pecl install libsodium-2.0.23 \
    && echo 'extension=sodium.so' >> /etc/php.ini ; \
    fi

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
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/bin --filename=composer

# Symfony cli
RUN curl -s https://get.symfony.com/cli/installer | bash \
    &&  mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# FPM
COPY ./www.conf /etc/php-fpm.d/www.conf

# Custom php config
COPY ./custom/* /etc/php.d/

USER www-data

EXPOSE 9000

CMD ["/usr/sbin/php-fpm --nodaemonize"]