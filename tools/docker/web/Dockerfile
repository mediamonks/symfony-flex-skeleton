FROM amazonlinux:2

RUN yum -y install -y shadow-utils
RUN useradd -ms /bin/bash -d /var/www www-data

RUN yum -y update
RUN yum install -y amazon-linux-extras

RUN amazon-linux-extras enable nginx1.12
RUN yum clean metadata
RUN yum install -y nginx

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./www.conf /etc/nginx/conf.d/www.conf

RUN ln -sf /dev/stdout /var/log/nginx/project_access.log;
RUN ln -sf /dev/stderr /var/log/nginx/project_error.log;

RUN chown -R www-data:www-data /var/log/nginx
RUN chown -R www-data:www-data /var/lib/nginx

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]