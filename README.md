# Symfony Backoffice


## Étapes d'installation

1. **Installation des dépendances**  
   ```
   composer install
   ```

2. **Création de la base de données**  
   Si besoin, modifiez la variable `DATABASE_URL` dans le fichier `.env`, puis créez la base de données :  
   ```
   php bin/console doctrine:database:create
   ```

3. **Exécution des migrations**   
   ```
   php bin/console doctrine:migration:migrate
   ```

4. **Importation des fixtures**  
   Chargez les données initiales :  
   ```
   php bin/console doctrine:fixtures:load
   ```

5. **Compilation des assets CSS**  
   Construisez le CSS :  
   ```
   php bin/console tailwind:build
   ```


## Commandes personnalisées

Créer un client :
```
php bin/console app:client:create
```

Importer des produits à partir d'un fichier CSV situé dans le dossier public/csv/
```
php bin/console app:import:csv
```


## Exécution des Tests

Pour lancer l'ensemble des tests :
```
php bin/phpunit
```


## Fonctionnalités Implémentées

- **Gestion des utilisateurs**  
  - Lister, ajouter, modifier et supprimer des utilisateurs.

- **Gestion des produits**  
  - Importer une liste de produits via un fichier CSV.
  - Lister, ajouter, modifier et supprimer des produits.
  - Exporter la liste des produits.

- **Gestion des clients**  
  - Lister, ajouter, modifier et supprimer des clients.


## Accès et Identifiants

### Administrateur

- **Email :** `admin@example.com`  
- **Mot de passe :** `adminpass`

### Gestionnaire

- **Email :** `manager1@example.com`  
- **Mot de passe :** `managerpass1`

### Utilisateur

- **Email :** `user1@example.com`  
- **Mot de passe :** `userpass1`
