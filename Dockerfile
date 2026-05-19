FROM php:8.2-apache

# Modules Apache
RUN a2enmod rewrite deflate expires headers

# PHP intl + Node.js + npm
RUN apt-get update && apt-get install -y libicu-dev nodejs npm \
    && docker-php-ext-install intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# AllowOverride All global (couvre .htaccess dans /var/www/html)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/sites-available/000-default.conf

# Limites headers Apache (131072 = gère les gros cookies localhost)
RUN echo "LimitRequestFieldSize 131072" >> /etc/apache2/apache2.conf \
    && echo "LimitRequestLine 131072" >> /etc/apache2/apache2.conf \
    && echo "LimitRequestFields 200" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . /var/www/html/

RUN npm install && npm run build && rm -rf node_modules

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
