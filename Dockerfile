FROM php:8.2-cli

ARG user
ARG uid

RUN apt-get update && apt-get install -y \
    openssl \
    unzip \
    libicu-dev \
    libzip-dev \
    locales

RUN docker-php-ext-configure \
    intl

RUN docker-php-ext-install -j$(nproc) \
    intl \
    opcache \
    zip

# Locale
RUN sed -i -e \
  's/# ru_RU.UTF-8 UTF-8/ru_RU.UTF-8 UTF-8/' /etc/locale.gen \
   && locale-gen

ENV LANG ru_RU.UTF-8
ENV LANGUAGE ru_RU:ru
ENV LC_LANG ru_RU.UTF-8
ENV LC_ALL ru_RU.UTF-8

# +Timezone (если надо на этапе сборки)
ENV TZ Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Install composer and set COMPOSER_HOME to writable directory
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_HOME /tmp/.composer

WORKDIR /api-invoker

USER $user
