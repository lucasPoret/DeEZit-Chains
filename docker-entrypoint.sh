#!/bin/bash
set -e

# Corriger les permissions des dossiers nécessaires au démarrage
# (nécessaire car les volumes montés peuvent avoir de mauvaises permissions)
chown -R www-data:www-data /var/www/html/json /var/www/html/lvl_crea 2>/dev/null || true
chmod -R 775 /var/www/html/json /var/www/html/lvl_crea 2>/dev/null || true

# S'assurer que level_time_trial.json existe et a les bonnes permissions
if [ -f /var/www/html/json/level_time_trial.json ]; then
    chmod 664 /var/www/html/json/level_time_trial.json
    chown www-data:www-data /var/www/html/json/level_time_trial.json
else
    touch /var/www/html/json/level_time_trial.json
    echo "{}" > /var/www/html/json/level_time_trial.json
    chmod 664 /var/www/html/json/level_time_trial.json
    chown www-data:www-data /var/www/html/json/level_time_trial.json
fi

# Exécuter la commande par défaut (Apache)
exec "$@"

