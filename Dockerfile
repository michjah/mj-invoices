FROM php:8.5-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip npm \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Stage 2: Node.js for frontend
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Install JS dependencies
COPY package*.json ./
RUN npm install --legacy-peer-deps

# Expose ports for PHP and Vite
EXPOSE 8000 5173

CMD sh -c "php artisan serve --host=0.0.0.0 --port=8000 & npm run dev -- --host"
