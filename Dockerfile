# Livego Ticketera — PHP 8.2 + Apache
FROM php:8.2-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends libicu-dev libpq-dev \
 && docker-php-ext-install pdo_mysql pdo_pgsql intl \
 && a2enmod rewrite headers \
 && rm -rf /var/lib/apt/lists/*

# La web se sirve desde app/webroot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/app/webroot
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf \
 && { echo 'upload_max_filesize=20M'; echo 'post_max_size=25M'; echo 'date.timezone=America/Argentina/Buenos_Aires'; echo 'expose_php=Off'; } > /usr/local/etc/php/conf.d/livego.ini

COPY . /var/www/html/
RUN mkdir -p app/tmp/cache/models app/tmp/cache/persistent app/tmp/cache/views app/tmp/logs app/tmp/sessions app/webroot/img/eventos \
 && chown -R www-data:www-data app/tmp app/webroot/img \
 && chmod +x /var/www/html/bin/docker-entrypoint.sh

ENV PORT=80
EXPOSE 80
ENTRYPOINT ["/var/www/html/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
