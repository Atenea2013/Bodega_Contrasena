FROM php:8.2-apache

# 1. Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install zip pdo_mysql

# 2. Habilitar mod_rewrite de Apache para las URLs de Laravel
RUN a2enmod rewrite

# 3. Configurar el directorio raíz de Apache para que apunte a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 4. Instalar Composer (el gestor de paquetes)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Establecer directorio de trabajo
WORKDIR /var/www/html

# 6. Copiar todos los archivos del proyecto al contenedor
COPY . .

# 7. Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 8. Dar permisos a las carpetas de almacenamiento
RUN chown -R www-data:www-data storage bootstrap/cache

# 9. Configurar el puerto para que Render lo detecte (Render usa la variable PORT)
RUN sed -i "s/80/\${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 10. Comando de arranque
CMD ["sh", "-c", "php artisan migrate --force && apache2-foreground"]
