FROM php:8.4-apache

# Install system dependencies for ZIP support
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions
RUN docker-php-ext-install session zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Create directories
RUN mkdir -p /var/www/html/zettels /var/www/html/config

# Set proper ownership and permissions
# Note: zettels directory needs write permissions for www-data
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/zettels

# Apache configuration for security
RUN echo '<Directory /var/www/html/>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n\
\n\
<DirectoryMatch "^.*/config/">\n\
    Require all denied\n\
</DirectoryMatch>\n\
\n\
<DirectoryMatch "^\\..*">\n\
    Require all denied\n\
</DirectoryMatch>' > /etc/apache2/conf-available/zettelkasten.conf && \
    a2enconf zettelkasten

# Make sure www-data can write to mounted volume
# This will be overridden by the volume mount, but helps if running without volumes
RUN chown www-data:www-data /var/www/html/zettels

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
