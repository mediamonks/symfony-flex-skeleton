FROM mediamonks/apachephp:7.1

#Permission to Assembla GIT Repositories
COPY ./id_rsa /root/.ssh/id_rsa
COPY ./id_rsa.pub /root/.ssh/id_rsa.pub

# Default apache2 configuration
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

##SSL extra's
COPY ./ssl.crt /etc/ssl/certs/server.crt
COPY ./ssl.key /etc/ssl/private/server.key
COPY ./apache-ssl.conf /etc/apache2/sites-available/000-default-ssl.conf
RUN a2enmod ssl
RUN a2ensite 000-default-ssl

CMD ["apache2ctl", "-D", "FOREGROUND"]