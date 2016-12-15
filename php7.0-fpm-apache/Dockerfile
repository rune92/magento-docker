FROM php:7.0-apache

MAINTAINER David Kovacs <davedosz@gmail.com>

RUN build_packages="libmcrypt-dev libpng12-dev libfreetype6-dev libjpeg62-turbo-dev libxml2-dev libxslt1-dev libmemcached-dev libmemcached-tools vim mc wget git npm memcached" \
    && apt-get update && apt-get install -y $build_packages \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install mcrypt \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install soap \
    && docker-php-ext-install xsl \
    && docker-php-ext-install zip \
    && docker-php-ext-install json \
    && apt-get -y update \
    && apt-get autoremove && apt-get autoclean  \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install memcached
RUN git clone https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached \
  && cd /usr/src/php/ext/memcached && git checkout -b php7 origin/php7 \
  && docker-php-ext-configure memcached \
  && docker-php-ext-install memcached \
  && docker-php-ext-enable memcached

RUN systemctl enable memcached

COPY ioncube/   /opt/ioncube

RUN mkdir -p /usr/local/ioncube \
    && cp /opt/ioncube/ioncube_loader_lin_7.0.so  /usr/local/ioncube \
    && chmod 644 /usr/local/ioncube/ioncube_loader_lin_7.0.so \
    && echo "zend_extension = /usr/local/ioncube/ioncube_loader_lin_7.0.so" > /usr/local/etc/php/conf.d/ioncube.ini

COPY php.ini /usr/local/etc/php/conf.d/magento.ini

RUN a2enmod rewrite headers

COPY magento.conf /etc/apache2/conf-enabled/

EXPOSE 80 443

ADD sendmail.sh /sendmail.sh
ADD dockerscript.sh /dockerscript.sh
ADD core_config_script.php /core_config_script.php
ADD magento-local-cache.xml /magento-local-cache.xml

RUN chmod +x /sendmail.sh
RUN chmod +x /dockerscript.sh

CMD ["apache2-foreground"]
