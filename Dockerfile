# Use a multi-stage build
# Stage 1: Build environment
FROM php:8.2-cli AS build

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql gd dom

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts

# Stage 2: Production environment
FROM php:8.2-cli

# Copy application files from build environment
COPY --from=build /var/www/html /var/www/html

# Set working directory
WORKDIR /var/www/html

# Expose the port the app runs on
EXPOSE 9999 

# Command to run the application
CMD ["php", "-S", "0.0.0.0:9999", "-t", "public"]
