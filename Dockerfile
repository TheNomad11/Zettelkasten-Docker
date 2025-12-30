# Dockerfile
FROM php:8.4-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql \
    && a2enmod rewrite

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy source files
COPY src/ /var/www/html/
COPY config/ /var/www/config/

# Set permissions for zettels directory
RUN mkdir -p /var/www/html/zettels && \
    chown -R www-data:www-data /var/www/html/zettels && \
    chmod -R 755 /var/www/html/zettels
    

# Expose port 80
EXPOSE 80
