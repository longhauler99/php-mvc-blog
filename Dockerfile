# Use the official PHP image as the base image
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
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

# Copy the rest of the application files into the container
COPY . /var/www/html/

# Expose the port the app runs on
EXPOSE 9999 

# Command to run the application
CMD ["php", "-S", "0.0.0.0:9999", "-t", "public"]
