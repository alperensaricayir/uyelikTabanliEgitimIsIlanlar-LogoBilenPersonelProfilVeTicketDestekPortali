# 1. Aşama: Frontend (Node.js) derlemesi
FROM node:20 AS frontend
WORKDIR /app
COPY . .
# NPM paketlerini yükle ve arayüzü (Vite/Tailwind) derle
RUN npm install
RUN npm run build

# 2. Aşama: Backend (PHP 8.3) ve Sunucu Ayarları
FROM php:8.3-cli

# Gerekli sistem paketlerini (SQLite vb.) yükle
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libsqlite3-dev \
    libzip-dev \
    libicu-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP Eklentilerini (SQLite, GD, Intl vb.) kur
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd intl zip

# Composer'ı global olarak indir
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Çalışma dizinini ayarla
WORKDIR /var/www

# Kodları sunucuya kopyala
COPY . .
# İlk aşamada (Frontend) derlenen css/js dosyalarını kopyala
COPY --from=frontend /app/public/build /var/www/public/build

# PHP Paketlerini yükle
RUN composer install --no-dev --optimize-autoloader

# Boş bir SQLite veritabanı dosyası oluştur
RUN touch database/database.sqlite

# Klasör izinlerini ver (Render'da hata vermemesi için)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database

# Render otomatik olarak PORT belirler
ENV PORT=10000
EXPOSE ${PORT}

# Sistemi Başlat (Önce resim linki oluştur, sonra veritabanını kur, sonra projeyi ayağa kaldır)
CMD php artisan storage:link && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT}
