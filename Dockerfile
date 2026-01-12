FROM php:8.0-apache

# Installer les outils de compilation nécessaires
RUN apt-get update && apt-get install -y \
    gcc \
    make \
    && rm -rf /var/lib/apt/lists/*

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

# Copier le script d'initialisation
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copier les fichiers de l'application
COPY . /var/www/html/

# Supprimer les binaires pré-compilés (incompatibles avec le conteneur)
RUN rm -f /var/www/html/php/randomGenerate /var/www/html/php/solver 2>/dev/null || true

# Compiler randomGenerate depuis le code source
RUN cd /var/www/html/générateur && \
    gcc -o /var/www/html/php/randomGenerate main.c -std=c11 -Wall && \
    chmod +x /var/www/html/php/randomGenerate && \
    /var/www/html/php/randomGenerate 1234567890 3 > /dev/null 2>&1 || (echo "Erreur: randomGenerate ne fonctionne pas" && exit 1)

# Compiler solver si nécessaire (si le code source existe)
RUN if [ -f /var/www/html/Solveur/solver2.0.c ]; then \
    cd /var/www/html/Solveur && \
    gcc -o /var/www/html/php/solver solver2.0.c -std=c11 -lm -Wall && \
    chmod +x /var/www/html/php/solver; \
    fi || true

# Rendre les exécutables exécutables (au cas où ils existent déjà)
RUN chmod +x /var/www/html/php/randomGenerate 2>/dev/null || true
RUN chmod +x /var/www/html/php/solver 2>/dev/null || true

# Définir les permissions appropriées
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Donner les permissions d'écriture aux dossiers nécessaires (pour les fichiers générés)
RUN chown -R www-data:www-data /var/www/html/json && \
    chmod -R 775 /var/www/html/json && \
    touch /var/www/html/json/level_time_trial.json && \
    chown www-data:www-data /var/www/html/json/level_time_trial.json && \
    chmod 664 /var/www/html/json/level_time_trial.json && \
    mkdir -p /var/www/html/lvl_crea && \
    chown -R www-data:www-data /var/www/html/lvl_crea && \
    chmod -R 775 /var/www/html/lvl_crea

# Définir le point d'entrée
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]

EXPOSE 80

