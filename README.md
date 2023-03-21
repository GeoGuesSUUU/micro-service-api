# MicroService & API REST

## Prérequis
- [PHP 8.1+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/download/)
- [MariaDB](https://mariadb.org/download/)
- [GitBash](https://git-scm.com/downloads) ou un Terminal Linux

## Installation
1. Cloner le project
    ```shell
    git clone https://github.com/GeoGuesSUUU/micro-service-api.git
    ```
2. Configuration des variables d'environnement
    - Renommer le fichier `.env.example` en `.env`
    - Dans ce fichier, modifier les variables suivantes :
      - **DATABASE_URL** : Url du server de base de données (MariaDB) avec la base utilisé pour ce project
        - Remplacer `DB_USER_NAME` par le nom d'utilisateur de votre base de données
        - Remplacer `DB_NAME` par le nom de votre base de données
           ```
           DATABASE_URL="mysql://DB_USER_NAME:@127.0.0.1:3306/DB_NAME?serverVersion=mariadb-10.10.0&charset=utf8mb4"
           ```
      - **JWT_PASSPHRASE** : La passphrase qui sera utilisé pour générer les clé privé et public
          - Remplacer `SECRET` par la passphrase de votre choix
             ```
             JWT_PASSPHRASE=SECRET
             ```
      - **MAILER_DSN** : Le DSN du Mailer Google ([si vous n'avez pas identifiant mailer Google](#comment-créer-un-mailer-google))
          - Remplacer `MAIL_ADDRESS` par l’addresse mail du compte
          - Remplacer `PASSWORD` par le mot de pass d’application
             ```
             MAILER_DSN=gmail://MAIL_ADDRESS:PASSWORD@default
             ```
3. Générer les clé privée et publique
Pour cette partie vous aurez besoin d'un terminal GitBash ou basé Linux.
   - En premier, générer la clé privé, executer la commande suivante et entrer votre passphrase précédement utilisé pour la varible d'environnement 
    ```shell
     openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    ```
   - Et ensuite, générer la clé publique, executer la commande suivante et entrer une nouvelle fois votre passphrase
    ```shell
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    ```
4. Base de données, Migrations et Fixtures
   - Créer votre base de données :
      ```shell
      php bin/console doctrine:database:create
      ```
   - Executer les migrations :
     ```shell
     php bin/console doctrine:migration:migrate
     ```
   - Si vous voulez initialiser votre base de données avec quelques données, executer les fixtures :
     ```shell
     php bin/console doctrine:fixtures:load
     ```
5. Lancer le projet :
     ```shell
     php -S localhost:8000 -t ./public
     ```


## Utilisation
api
### Swagger Page
url

## Aide
### Comment créer un mailer google
1. Créer compte Google
2. Activer double authentification
3. Ajouter un mot de passe d'application
