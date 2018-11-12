FROM amazonlinux:1

RUN yum -y groupinstall "Development Tools"
RUN useradd -ms /bin/bash -d /var/www www-data

RUN yum -y update
RUN yum install -y nginx

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./www.conf /etc/nginx/conf.d/www.conf

RUN ln -sf /dev/stdout /var/log/nginx/project_access.log;
RUN ln -sf /dev/stderr /var/log/nginx/project_error.log;

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]