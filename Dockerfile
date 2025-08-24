# Base image with Apache and PHP 8.2
FROM php:8.2-apache

# Install PHP extensions needed by CakePHP
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    git \
  && docker-php-ext-install pdo_mysql intl mbstring \
  && docker-php-ext-enable intl

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy application code
COPY . /var/www/html

# Install Composer for dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set permissions for tmp and logs
RUN chown -R www-data:www-data /var/www/html/tmp /var/www/html/logs

# Expose default HTTP port
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
