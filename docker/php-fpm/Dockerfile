FROM php:8.1-fpm-alpine
WORKDIR "/application"

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install dev dependencies
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    --update linux-headers \
    curl-dev \
    imagemagick-dev \
    libtool \
    libxml2-dev \
    sqlite-dev \
    libpq-dev

# Install production dependencies
RUN apk add --no-cache \
    bash \
    curl \
    g++ \
    gcc \
    git \
    imagemagick \
    libc-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    make \
    openssh-client \
    zlib-dev \
    libzip-dev \
    icu-dev

RUN apk add --no-cache shadow git openssh-client

# Install and enable php extensions

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

RUN install-php-extensions redis

RUN docker-php-ext-install \
    calendar \
    exif \
    pcntl \
    gd \
    zip \
    bcmath \
    soap \
    intl \
    pdo \
    pdo_pgsql

# Install xdebug
RUN pecl install xdebug \
    && pecl install imagick \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable imagick


# Xdebug setup
RUN echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=no" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN groupmod -o -g 1000 www-data && \
    usermod -o -u 1000 -g www-data www-data

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN chown -R www-data:www-data /application

# Change www-data user to match the host system UID and GID and chown www directory
RUN apk --no-cache add shadow \
  && usermod --non-unique --uid 1000 www-data \
  && groupmod --non-unique --gid 1000 www-data \
  && chown -R www-data:www-data /application
