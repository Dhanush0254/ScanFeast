# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    zip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy app files into container
COPY . .

# Copy .env file separately if needed
# (Optional, only needed if your build context excludes it)
# COPY .env .env

# Install PHP dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
