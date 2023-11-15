FROM php:8.2-alpine

WORKDIR /var/www

RUN apk add --no-cache openssl bash postgresql-dev curl
RUN docker-php-ext-install pdo pdo_pgsql

RUN addgroup --g 1000 appgroup && \
    adduser -u 1000 -G appgroup -h /home/appuser -D appuser

COPY --chown=appuser:appuser ./users-app /var/www
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN composer install

USER appuser

CMD php artisan serve --host=0.0.0.0 --port=8000

EXPOSE 8000