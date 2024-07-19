FROM php:8.3-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libjpeg-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libwebp-dev \
    zip \
    unzip \
    nano \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config  \
    libssl-dev \
    openssl \
    libz-dev \
    libmemcached-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure ftp --with-openssl-dir=/usr \
	&& docker-php-ext-install ftp

# Install PECL extensions
RUN pecl install mongodb \
    && echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongo.ini

# Install PECL memcached
RUN pecl install memcached
RUN echo extension=memcached.so >> $PHP_INI_DIR/conf.d/memcached.ini

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-webp

RUN docker-php-ext-install -j$(nproc) \
    iconv \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    sockets

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Expose needed ports
EXPOSE 80 443 25

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user
