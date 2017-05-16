FROM mediamonks/apachephp:7.0
RUN apt-get install php7.0-soap
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./php.ini /etc/php/7.0/apache2/php.ini
RUN apt-get install php7.0-sybase -y
RUN apt-get install php7.0-odbc -y
RUN apt-get install tdsodbc -y
RUN sed -i '14i tds version = 8.0' /etc/freetds/freetds.conf
RUN sed -i '14i client charset = UTF-8' /etc/freetds/freetds.conf
CMD ["apache2ctl", "-D", "FOREGROUND"]