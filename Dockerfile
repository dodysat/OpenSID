# Use the official PHP 8.0 image with Apache
FROM php:8.0-apache

# Update package list and install dependencies
RUN apt-get update && apt-get install -y \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libwebp-dev \
  libxpm-dev \
  libzip-dev \
  libtidy-dev \
  libonig-dev \
  libxml2-dev \
  --no-install-recommends

# Configure and install PHP extensions
RUN docker-php-ext-configure gd \
  --with-freetype \
  --with-jpeg \
  --with-webp \
  --with-xpm

RUN docker-php-ext-install -j$(nproc) \
  curl \
  exif \
  fileinfo \
  gd \
  iconv \
  mbstring \
  mysqli \
  tidy \
  zip

# Enable Apache mod_rewrite if needed
RUN a2enmod rewrite

# Clean up to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy your application code to the container (if applicable)
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html
