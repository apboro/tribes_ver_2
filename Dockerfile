ARG COMPOSER_VERSION="2.1.5"
ARG PHP_VERSION="7.4.30"
ARG NODE_VERSION="14.20"
ARG NGINX_VERSION="1.23.1"
ARG WORKDIR="/app"

FROM node:${NODE_VERSION} as frontend-builder
ARG WORKDIR
WORKDIR ${WORKDIR}
COPY package.json package-lock.json ${WORKDIR}/
RUN npm ci
COPY public ${WORKDIR}/public/
COPY resources ${WORKDIR}/resources/
COPY *.js artisan ${WORKDIR}/
# COPY app ${WORKDIR}/app/
RUN npm run prod

FROM php:${PHP_VERSION}-fpm-alpine3.15
ARG WORKDIR
WORKDIR ${WORKDIR}

RUN apk add --no-cache nginx gettext tzdata ca-certificates && rm /etc/nginx/http.d/*

RUN apk --no-cache add php-pgsql postgresql14-dev pcre-dev libjpeg-turbo-dev libpng-dev php7-imagick libzip-dev freetype-dev php7-bcmath $PHPIZE_DEPS \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && rm -rf /tmp/pear \
  && docker-php-ext-install pdo \
  && docker-php-ext-install pdo_pgsql \
  && docker-php-ext-configure gd --with-jpeg --with-freetype  \
  && docker-php-ext-install gd \
  && docker-php-ext-install zip \
  && docker-php-ext-install bcmath \
  && docker-php-ext-install curl \
  && apk del pcre-dev $PHPIZE_DEPS

COPY .docker/docker-entrypoint.sh /
COPY .docker/*.conf.template /etc/nginx/templates/
COPY .docker/fpm.conf /usr/local/etc/php-fpm.conf
ENTRYPOINT ["/docker-entrypoint.sh"]
CMD [""]

# Graceful shutdown for nginx
STOPSIGNAL SIGQUIT

# nginx and php-fpm
EXPOSE 80 9000

COPY composer.* .env.example artisan ${WORKDIR}/
RUN cp -v .env.example .env && \
    php composer.phar install --no-dev --no-autoloader --prefer-dist

COPY --chown=www-data:www-data . ${WORKDIR}/
RUN php composer.phar install --no-dev --optimize-autoloader --prefer-dist
COPY --chown=www-data:www-data --from=frontend-builder ${WORKDIR}/public ${WORKDIR}/public/
