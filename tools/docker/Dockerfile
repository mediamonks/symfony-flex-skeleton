FROM mediamonks/apachephp:7.0
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./php.ini /etc/php/7.0/apache2/php.ini
CMD ["apache2ctl", "-D", "FOREGROUND"]