# DeEZit-Chains

Application web PHP avec base de données MySQL pour le jeu DeEZit Chain.

## Installation avec Docker Compose

### Prérequis
- Docker
- Docker Compose
- Nginx Proxy Manager avec le réseau `npm` créé

### Démarrage rapide

1. Cloner ou télécharger ce projet
2. Lancer l'application avec Docker Compose :
```bash
docker-compose up -d
```

3. Configurer Nginx Proxy Manager :
   - Créer un nouveau proxy host dans Nginx Proxy Manager
   - Domain Names : `deezit_chain.lucas-serv.duckdns.org`
   - Scheme : `http`
   - Forward Hostname/IP : `deezit_chain_web`
   - Forward Port : `80`
   - Activer "Websockets Support" si nécessaire

4. Accéder à l'application :
   - Application web : https://deezit_chain.lucas-serv.duckdns.org/php/index.php
   - Alternative : https://deezit_chain.lucas-serv.duckdns.org (redirige automatiquement vers php/index.php)

**Note importante** : 
- La base de données est automatiquement initialisée au premier démarrage avec le fichier `BD/deezit_chain.sql`.
- Les conteneurs utilisent le réseau externe `npm` (Nginx Proxy Manager), donc les ports ne sont pas exposés directement sur l'hôte.

### Commandes utiles

- Démarrer les services : `docker-compose up -d`
- Arrêter les services : `docker-compose down`
- Voir les logs : `docker-compose logs -f`
- Reconstruire les images : `docker-compose build --no-cache`
- Accéder au conteneur web : `docker exec -it deezit_chain_web bash`
- Accéder à la base de données : `docker exec -it deezit_chain_db mysql -uroot -proot deezit_chain`

### Configuration

Les variables d'environnement peuvent être modifiées dans le fichier `docker-compose.yml` :
- `DB_HOST` : Nom du service de base de données (par défaut : `db`)
- `DB_USER` : Utilisateur MySQL (par défaut : `root`)
- `DB_PASSWORD` : Mot de passe MySQL (par défaut : `root`)
- `DB_NAME` : Nom de la base de données (par défaut : `deezit_chain`)

**Configuration réseau** : Les conteneurs utilisent le réseau externe `npm` géré par Nginx Proxy Manager. Aucun port n'est exposé directement sur l'hôte. L'accès se fait via le domaine `deezit_chain.lucas-serv.duckdns.org`.

### Persistance des données

Les données de la base de données sont persistées dans un volume Docker nommé `db_data`. Pour supprimer complètement les données :
```bash
docker-compose down -v
```

### Structure

- `php/` : Fichiers PHP de l'application
- `BD/deezit_chain.sql` : Script d'initialisation de la base de données
- `css/`, `js/`, `image/`, `sound/`, etc. : Fichiers statiques
- `Dockerfile` : Configuration de l'image Docker pour PHP/Apache
- `docker-compose.yml` : Configuration Docker Compose