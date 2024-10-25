# Use an official Ubuntu base image
FROM ubuntu:22.04

# Set the working directory
WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Europe/Berlin

# Update the package list and install basic packages
RUN apt-get update && apt-get install -y \
    tzdata \
    software-properties-common curl git unzip zip \
    && ln -fs /usr/share/zoneinfo/$TZ /etc/localtime \
    && dpkg-reconfigure --frontend noninteractive tzdata \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update

# Install PHP and required extensions
RUN apt-get install -y \
    php8.1 php8.1-cli php8.1-common php8.1-fpm \
    php8.1-zip php8.1-mbstring php8.1-xml php8.1-curl php8.1-gd php8.1-intl \
    php8.1-mysql php8.1-sqlite3 php8.1-pgsql php8.1-opcache

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Install Node.js and Yarn for frontend assets management (optional)
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g yarn

# Set up permissions (optional but recommended)
RUN useradd -ms /bin/bash symfony 
    #&& chown -R symfony:symfony /app

RUN apt-get update && apt-get install sudo

# Set the working directory
WORKDIR /app
