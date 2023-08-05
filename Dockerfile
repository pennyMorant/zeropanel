# 使用 PHP 8.2 FPM 镜像作为基础镜像
FROM php:8.2-fpm

# 安装依赖
RUN apt-get update && apt-get install -y \
    wget \
    libzip-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    zlib1g-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_pgsql mysqli mbstring zip exif pcntl bcmath opcache gd soap intl \
    && docker-php-ext-enable pdo_mysql pdo_pgsql mysqli mbstring zip exif pcntl bcmath opcache gd soap intl \
    && rm -rf /var/lib/apt/lists/*

# 将本地代码复制到容器中
COPY . /var/www/html

# 设置工作目录
WORKDIR /var/www/html

# 下载和安装 Composer
RUN wget https://getcomposer.org/installer -O composer.phar \
    && php composer.phar \
    && php composer.phar install

# 设置权限
RUN chmod -R 755 . \
    && chown -R www-data:www-data .

# 开启 PHP-FPM
CMD ["php-fpm"]