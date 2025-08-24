# Use official PHP image with Apache
FROM php:8.2-apache

# Install necessary PHP extensions for CakePHP & MySQL
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy your CakePHP app
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install CakePHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/tmp /var/www/html/logs

# Expose port 80
EXPOSE 80

# Run Apache
CMD ["apache2-foreground"]
