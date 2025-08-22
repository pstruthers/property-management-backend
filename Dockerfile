# Use an official PHP image with Apache
FROM php:8.2-apache

# Install required PHP extensions for CakePHP
RUN docker-php-ext-install pdo pdo_mysql intl

# Enable Apache rewrite module (needed for CakePHP routes)
RUN a2enmod rewrite

# Copy app into container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Set permissions (CakePHP needs writable tmp/logs/tmp/cache)
RUN chown -R www-data:www-data /var/www/html/tmp /var/www/html/logs

# Expose Apache port
EXPOSE 80
