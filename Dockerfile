FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl \
    libpng-dev libonig-dev \
    default-mysql-client \
    && docker-php-ext-install pdo mbstring zip gd pdo_mysql

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node 18
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app
COPY . .

RUN composer install --ignore-platform-req=ext-gd --no-scripts --no-interaction

RUN npm install --legacy-peer-deps --ignore-scripts \
    && npm uninstall sass \
    && npm install sass@1.32.13 --save-dev \
    && npm install resolve-url-loader@^5.0.0 --save-dev --legacy-peer-deps

RUN sed -i 's/&\.bg-/\.bg-/g' node_modules/admin-lte/build/scss/mixins/_backgrounds.scss || true

# assets
RUN npm run prod

# clear devDependencies
RUN npm prune --omit=dev --ignore-scripts

# Laravel cache & permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R a+rw storage

RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear
RUN php artisan event:clear

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
