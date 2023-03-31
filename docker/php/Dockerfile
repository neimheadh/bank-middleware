ARG PHP_VERSION=8.1
FROM php:${PHP_VERSION}-fpm

# Install required APT packages
RUN apt-get update \
 && apt-get install -y \
        git \
        libpq-dev \
        libzip-dev \
        libicu-dev \
        libsqlite3-dev \
        unzip \
 && rm -Rf /var/lib/apt/lists/*

# Install composer
RUN curl -o composer-setup.php https://getcomposer.org/installer \
 && php composer-setup.php \
 && rm composer-setup.php \
 && mv composer.phar /usr/local/bin/composer \
 && chmod +x /usr/local/bin/composer \
 && composer --version

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip intl pdo_sqlite

# Install xdebug with coverage
ARG XDEBUG_VERSION=""
RUN pecl install xdebug \
 && docker-php-ext-enable xdebug \
 && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Configure environment
ARG USER_ID=1000
ARG GROUP_ID=1000
RUN groupadd --gid ${GROUP_ID} developer \
 && useradd -g developer -u ${USER_ID} -s /bin/bash -m developer
USER developer