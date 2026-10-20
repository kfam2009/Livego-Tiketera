#!/bin/sh
set -e
# Render (y otros) indican el puerto en $PORT
if [ -n "$PORT" ] && [ "$PORT" != "80" ]; then
  sed -ri "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
  sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf
fi
# Apache tiene que ver las variables de entorno (DB_*, APP_DEBUG…)
php /var/www/html/bin/init.php || echo "[init] Aviso: la inicialización falló, la app arranca igual."
rm -rf /var/www/html/app/tmp/cache/models/* /var/www/html/app/tmp/cache/persistent/* 2>/dev/null || true
chown -R www-data:www-data /var/www/html/app/tmp /var/www/html/app/webroot/img
exec "$@"
