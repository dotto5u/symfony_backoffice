## Utilisation

1. Intaller les dépendances `composer install`
2. Créer la base de données (changer le DATABASE_URL dans le .env si besoin) `php bin/console doctrine:database:create`
3. Lancer les migrations `php bin/console doctrine:migration:migrate`
4. Importer les fixtures `php bin/console doctrine:fixtures:load`
5. Construire de CSS `php bin/console tailwind:build`


<u>Identifiants de l'administrateur</u>

email : admin@example.com 
password : adminpass

<u>Identifiants d'un gestionnaire</u>

email : manager1@example.com  
password : managerpass1

<u>Identifiants d'un utilisateur</u>

email : user1@example.com
password : userpass1
