# Use the official PHP image as the base image
FROM php

FROM php:latest

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libxml2-dev \
    libzip-dev \
    zlib1g-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli gd dom mbstring zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./

# Create a non-root user and change ownership of the working directory
RUN useradd -m composer && chown -R composer /var/www/html

# Switch to the non-root user
USER composer

# Install composer dependencies
RUN composer install --no-interaction --no-plugins --no-scripts --no-suggest

# Switch back to root to copy application files
USER root

# Copy the rest of the application files into the container
COPY . /var/www/html/

# Expose the port the app runs on
EXPOSE 9999 

# Command to run the application
CMD ["php", "-S", "0.0.0.0:9999", "-t", "public"]
