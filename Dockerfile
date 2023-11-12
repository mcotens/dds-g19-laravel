# Use the official PHP 8.2-cli image
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update -y \
    && apt-get install -y libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql

RUN apt-get update
RUN apt-get install -y p7zip-full

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the application files
COPY . .

# Install application dependencies
RUN composer install

# Generate Laravel application key
RUN php artisan key:generate

# Expose port 8000
EXPOSE 8000

# Run the Laravel development server
CMD php artisan serve --host=0.0.0.0 --port=8000
