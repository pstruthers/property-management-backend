# Use official PHP with Apache
FROM php:8.2-apache

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libicu-dev \
  libzip-dev \
  libonig-dev \
  zip \
  && docker-php-ext-install \
  pdo_mysql \
  intl \
  mbstring \
  zip \
  && docker-php-ext-enable intl mbstring zip

# Enable Apache mod_rewrite for CakePHP
RUN a2enmod rewrite

# Set Apache DocumentRoot to webroot
RUN sed -i 's|/var/www/html|/var/www/html/webroot|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/webroot|g' /etc/apache2/apache2.conf

# Copy project files
WORKDIR /var/www/html
# Create uploads folder
COPY . .
COPY certs /var/www/html/certs
RUN mkdir -p /var/www/html/webroot/uploads \
    && chown -R www-data:www-data /var/www/html/webroot/uploads \
    && chmod -R 755 /var/www/html/webroot/uploads

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Apache will serve app
EXPOSE 80

# Use Apache’s default entrypoint
CMD ["apache2-foreground"]
