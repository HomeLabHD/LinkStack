# syntax=docker/dockerfile:1
#
# LinkStack (HomeLabHD fork) container image.
#
# Built from this repository's own source so the image and the app are one artifact,
# tagged to the upstream LinkStack version it corresponds to. Mirrors the runtime of
# LinkStackOrg/linkstack-docker (Alpine + Apache + PHP 8.3, running as the unprivileged
# `apache` user) and adds a Composer stage — this source tree ships no vendor/, so
# dependencies (including the OpenID Connect provider) are resolved at build time.

# ---- Stage 1: Composer dependencies ------------------------------------------------
FROM composer:2 AS vendor

WORKDIR /app

# The full tree is needed so the optimized autoloader can build its classmap.
COPY . /app

# Production install only. Scripts are skipped (no artisan/package:discover at build);
# Laravel discovers packages on first boot. Platform reqs are validated in the runtime
# image, which carries the PHP extensions this resolver image does not.
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --ignore-platform-reqs \
 && rm -rf /app/.git /app/.docker /app/Dockerfile /app/.dockerignore

# ---- Stage 2: Runtime --------------------------------------------------------------
FROM alpine:3.23.2

LABEL org.opencontainers.image.title="LinkStack" \
      org.opencontainers.image.description="LinkStack (HomeLabHD fork) — Alpine/Apache/PHP, non-root, with generic OIDC SSO." \
      org.opencontainers.image.source="https://github.com/HomeLabHD/LinkStack" \
      org.opencontainers.image.licenses="AGPL-3.0-only"

EXPOSE 80 443

# Apache + the PHP 8.3 extension set LinkStack requires (matches upstream linkstack-docker).
RUN apk --no-cache --update add \
        apache2 \
        apache2-ssl \
        curl \
        php83-apache2 \
        php83-bcmath \
        php83-bz2 \
        php83-calendar \
        php83-common \
        php83-ctype \
        php83-curl \
        php83-dom \
        php83-fileinfo \
        php83-gd \
        php83-iconv \
        php83-json \
        php83-mbstring \
        php83-mysqli \
        php83-mysqlnd \
        php83-openssl \
        php83-pdo_mysql \
        php83-pdo_pgsql \
        php83-pdo_sqlite \
        php83-phar \
        php83-session \
        php83-xml \
        php83-tokenizer \
        php83-zip \
        php83-xmlwriter \
        php83-redis \
        tzdata \
 && mkdir /htdocs

# Application (with vendor/ from the composer stage — no Composer in the final image).
COPY --from=vendor /app /htdocs

# Apache + PHP configuration and entrypoint.
COPY .docker/configs/apache2/httpd.conf /etc/apache2/httpd.conf
COPY .docker/configs/apache2/ssl.conf /etc/apache2/conf.d/ssl.conf
COPY .docker/configs/php/php.ini /etc/php83/conf.d/40-custom.ini
COPY --chmod=0755 .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

# Ownership + least-privilege file modes; drop any set-uid/set-gid bits from the base.
RUN chown apache:apache /etc/ssl/apache2/server.pem /etc/ssl/apache2/server.key \
 && chown -R apache:apache /htdocs /etc/php83 \
 && find /htdocs -type d -print0 | xargs -0 chmod 0755 \
 && find /htdocs -type f -print0 | xargs -0 chmod 0644 \
 && find / -xdev -perm /6000 -type f -exec chmod a-s {} + 2>/dev/null || true

USER apache:apache

HEALTHCHECK CMD curl -f http://localhost -A "HealthCheck" || exit 1

WORKDIR /htdocs

CMD ["docker-entrypoint.sh"]
