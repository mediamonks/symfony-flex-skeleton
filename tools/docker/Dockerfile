FROM mediamonks/apachephp:7.0
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./apache-ssl.conf /etc/apache2/sites-available/000-default-ssl.conf
COPY ./ssl.crt /etc/ssl/certs/server.crt
COPY ./ssl.key /etc/ssl/private/server.key
RUN a2enmod ssl
RUN a2ensite 000-default-ssl
COPY ./php.ini /etc/php/7.0/apache2/php.ini
CMD ["apache2ctl", "-D", "FOREGROUND"]