FROM php:7.3.6-cli-alpine3.9

RUN apk add --no-cache \
        libzip-dev \
        openssl-dev \
        git && \
    docker-php-ext-install -j$(nproc) \
        zip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer && \
    rm -rf /usr/share/php7 && \
    mkdir /var/app

ENV PATH /var/app/bin:/var/app/vendor/bin:$PATH

WORKDIR /var/app
