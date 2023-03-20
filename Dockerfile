# Base image
FROM ubuntu:latest

# Update packages and install dependencies
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y curl gnupg2 tzdata

# set timezone to UTC
ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Add required keys and repositories for PHP 8.2, Nginx, etc.
RUN curl -fsSL https://nginx.org/keys/nginx_signing.key | apt-key add - \
    && echo "deb https://nginx.org/packages/mainline/ubuntu/ $(lsb_release -cs) nginx" > /etc/apt/sources.list.d/nginx.list \
    && apt-key adv --fetch-keys 'https://mariadb.org/mariadb_release_signing_key.asc' \
    && echo "deb [arch=amd64,arm64,ppc64el] https://mirrors.aliyun.com/mariadb/repo/10.11/ubuntu $(lsb_release -cs) main" > /etc/apt/sources.list.d/mariadb.list \
    && apt-get update

# Install PHP 8.2 and dependencies
RUN apt-get install -y php8.2-fpm php8.2-curl php8.2-gd php8.2-intl php8.2-mbstring \
php8.2-mysql php8.2-soap php8.2-xml php8.2-zip php8.2-bcmath \
php8.2-imagick php8.2-redis

# Install Nginx
RUN apt-get install -y nginx

# Remove the default Nginx configuration file
RUN rm /etc/nginx/conf.d/default.conf

# Add custom Nginx configuration
COPY nginx.conf /etc/nginx/conf.d/

# Install MariaDB
RUN apt-get install -y mariadb-server
WORKDIR /app
# Copy PHP code to /var/www/html
COPY . .

# Expose ports
EXPOSE 80 3306

# Start services
CMD service php8.2-fpm start \
 && service nginx start \
 && service mysql start \
 && tail -f /dev/null
