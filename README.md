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
   _Pour toutes les commandes suivantes placer vous au niveau du repertoire project `/micro-service-api`_
2. Installer les dépendances
    ```shell
    composer install
    ```
3. Configuration des variables d'environnement
    - Renommer le fichier `.env.example` en `.env`
    - Dans ce fichier, modifier les variables suivantes :
      - **DATABASE_URL** : Url du server de base de données (MariaDB) avec la base utilisé pour ce project
        - Remplacer `DB_USER_NAME` par le nom d'utilisateur de votre base de données
        - Remplacer `DB_USER_PASSWORD` par le mot de passe d'utilisateur de votre base de données
        - Remplacer `DB_NAME` par le nom de votre base de données
           ```
           DATABASE_URL="mysql://DB_USER_NAME:DB_USER_PASSWORD@127.0.0.1:3306/DB_NAME?serverVersion=mariadb-10.10.0&charset=utf8mb4"
           ```
          _*Vous pouvez aussi modifier la version mariadb si besoin._
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
4. Générer les clé privée et publique
Pour cette partie vous aurez besoin d'un terminal GitBash ou basé Linux.
   - En premier, générer la clé privé, executer la commande suivante et entrer votre passphrase précédement utilisé pour la varible d'environnement 
    ```shell
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    ```
   - Et ensuite, générer la clé publique, executer la commande suivante et entrer une nouvelle fois votre passphrase
    ```shell
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    ```
   ![](docs/openssl.png)

    _*Si vous n'avez pas le message "Entrer PEM pass phrase:", essayer d\'executer la commande depuis un IDE toujours avec le terminal Gitbash._
5. Base de données, Migrations et Fixtures
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
6. Lancer le projet :
     ```shell
     php -S localhost:8000 -t ./public
     ```


## Utilisation
En allant sur URL http://localhost:8000/ vous serez redirigé sur la page API doc (Swagger Page)

Plusieurs API sont générer par API Platform. Les API écrites correspondent au sections :
- Seller
- Store (Products, Commands & Slots)
- Auth & User
### Swagger Page
![](docs/swagger.png)

## Objectifs (Fonctionnalités)
### Principaux
- [x] En tant qu’utilisateur non connecté, je peux consulter la liste des magasins près de chez moi
- [x] En tant qu’utilisateur non connecté, je peux consulter les produits vendus dans ces magasins
- [x] En tant qu'utilisateur non connecté, je peux consulter si un produit est disponible en stock
- [x] En tant qu’utilisateur non connecté, je peux écrire un message à un vendeur
- [x] En tant qu'utilisateur non connecté, je peux m'inscrire afin de devenir un client

- [x] En tant que client, je peux commander un ou plusieurs produits dans un magasin
- [x] En tant que client, je dispose d'une liste de créneau disponible pour récupérer ma commande
- [x] En tant que client, je souhaite pouvoir réserver un créneau pour récupérer ma commande.

- [x] En tant qu’administrateur, je peux déclarer de nouvelles boutiques et mettre à jour le stock (si déjà existant, modifie le prix et/ou la quantité) en ajoutant ou retirant des articles.
- [x] En tant qu’administrateur, je peux notifier par mail mes clients que leur commande est validée.

### Secondaire
- [x] En tant que vendeur, je peux lire un message d’un client
- [x] En tant que vendeur, je peux ajouter ou modifier des créneaux

- [x] Swagger Page
- [x] Authentification JWT
- [ ] Authentification Oauth2
- [x] Pagination pour les reponses en liste
- [x] Actions disponibles intégré dans la reponse

## Aide
### Comment créer un mailer Google
1. Créer compte Google
2. Activer double authentification
3. Ajouter un mot de passe d'application

    ![](docs/google-mailer.png)
4. Ensuite pour la variable d'environnment MAILER_DNS, remplacer MAIL_ADDRESS par l'email et PASSWORD par le mot de passe de l'application
