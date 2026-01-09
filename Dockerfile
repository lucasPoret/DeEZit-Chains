FROM php:8.0-apache

# Activer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configurer PHP pour exposer les variables d'environnement
RUN echo "variables_order = \"EGPCS\"" > /usr/local/etc/php/conf.d/variables_order.ini

# Passer les variables d'environnement à Apache/PHP
RUN echo 'PassEnv DB_HOST DB_USER DB_PASSWORD DB_NAME' > /etc/apache2/conf-available/environment.conf && \
    a2enconf environment

# Configurer Apache pour que le document root pointe vers /var/www/html
# et permettre l'accès au dossier php
RUN a2enmod rewrite

# Copier la configuration Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copier les fichiers de l'application
COPY . /var/www/html/

# Rendre les exécutables exécutables (randomGenerate et solver pour Linux)
RUN chmod +x /var/www/html/php/randomGenerate 2>/dev/null || true
RUN chmod +x /var/www/html/php/solver 2>/dev/null || true

# Définir les permissions appropriées
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

EXPOSE 80

