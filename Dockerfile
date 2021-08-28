FROM php:5.6-apache

RUN apt-get update -y && \
    apt-get install -y \
        git \
        libonig-dev \
        libzip-dev \
        openssl \
        unzip \
        vim \
        wget \
        zip && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get autoremove -y && \
    apt-get clean all

RUN docker-php-ext-install \
        mbstring \
        mysqli \
        pdo \
        pdo_mysql \
        zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY docker/apache/site.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/

WORKDIR /srv

COPY --chown=www-data:www-data . .

RUN echo 'alias ll="ls -la --color"' >> ~/.bashrc
