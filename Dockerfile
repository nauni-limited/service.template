FROM php:8-fpm-alpine as base

ENV USER=user
ENV GROUP=docker
ENV UID=1000
ENV GID=127

RUN apk update \
    && apk upgrade \
    && apk add \
        icu-dev \
    && docker-php-ext-install \
        intl \
    && addgroup --gid $GID $GROUP \
    && adduser \
           --disabled-password \
           --gecos "" \
           --ingroup "$GROUP" \
           --uid "$UID" \
           "$USER"

WORKDIR /service

# Create a developer image for working on Service and running unit tests
FROM base AS dev

RUN curl -s https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin \
        --filename=composer \
    && apk add --no-cache \
        bash-completion \
        git \
        vim \
    && apk --no-cache add --virtual .build-dependency \
            g++ \
            autoconf \
            make \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-dependency

COPY docker/php/php.ini /usr/local/etc/php/
COPY docker/php/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/

USER $USER
RUN  wget https://get.symfony.com/cli/installer -O - | bash \
    && echo 'export PATH="~/.symfony/bin:$PATH"' >> ~/.bashrc \
    && echo 'export PATH="/service/vendor/bin:$PATH"' >> ~/.bashrc \
    && echo "alias service=/service/bin/console" >> ~/.bashrc \
    && echo "eval \$(service _completion --generate-hook --shell-type bash --program service)" >> ~/.bashrc