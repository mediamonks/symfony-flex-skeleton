FROM amazonlinux:2

# Packages
RUN yum -y install -y shadow-utils && \
    yum -y update && yum install -y amazon-linux-extras && \
    amazon-linux-extras enable nginx1 && \
    yum clean metadata && \
    yum install -y nginx && \
    yum clean all && \
    rm -rf /var/cache/yum

# User & Permissions
RUN useradd -ms /bin/bash -d /var/www www-data && \
    usermod -u 1000 www-data && \
    chown -R www-data:www-data /var/www

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./www.conf /etc/nginx/conf.d/www.conf

RUN ln -sf /dev/stdout /var/log/nginx/project_access.log && \
    ln -sf /dev/stderr /var/log/nginx/project_error.log;

RUN chown -R :www-data /var/log/nginx && \
    chown -R :www-data /var/lib/nginx

USER www-data

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]