FROM php:8.0-apache
# 777 COPY ./ /var/www/html/
COPY .env-example .env
COPY _archivos_apoyo/000-default.conf /etc/apache2/sites-available/
WORKDIR /var/www/html/

RUN apt-get update && \
    apt-get install -y libfreetype6-dev 
RUN apt-get install -y libjpeg62-turbo-dev 
RUN apt-get install -y libpng-dev 
RUN apt-get install -y mc 
RUN apt-get install -y nmap 
#RUN apt-get install -y git-all

#--RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
#--    docker-php-ext-install gd

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
    docker-php-ext-install gd

RUN apt-get install -y libpq-dev && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install pdo pdo_pgsql pgsql
    
# Dockerfile for Imagick (PHP7.X)
RUN apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/* \
    && printf "\n" | pecl install imagick \
    && docker-php-ext-enable imagick

RUN a2enmod rewrite

#RUN php composer.phar install
#RUN chmod 777 ./ -R && service apache2 restart


