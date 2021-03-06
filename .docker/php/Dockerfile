FROM php:8.1-fpm-alpine as base

ENV USER=user
ENV GROUP=docker
ENV UID=1000
ENV GID=1000

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

WORKDIR /var/local/src

# Create a developer image for working on Service and running unit tests
FROM base AS dev

COPY .docker/php/config/php.ini /usr/local/etc/php/

RUN apk add --no-cache \
        bash-completion \
        git \
        vim \
    && apk --no-cache add --virtual .build-dependency \
            g++ \
            autoconf \
            make \
    && pecl install xdebug \
    && apk del .build-dependency

USER $USER
RUN mkdir -p $HOME//bin \
    && curl -s https://getcomposer.org/installer | php -- \
            --install-dir=$HOME/bin \
            --filename=composer \
    && wget https://get.symfony.com/cli/installer \
        --output-document \
        - | bash \
    && mv $HOME/.symfony/bin/* $HOME/bin/ \
    && rm -rf $HOME/.symfony $HOME/.composer

# Add Xdebug to dev image in case we want to use it
FROM dev AS dev-xdebug

USER root
COPY .docker/php/config/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/
RUN docker-php-ext-enable xdebug

USER $USER