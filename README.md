
# NAVIFLY: Transport International & Livraison de Colis - Symfony Project 

Contexte Académique

Ce projet a été réalisé dans le cadre du module de **Développement Web Avancé (Symfony)** au sein de la formation d’ingénieur à **Esprit School of Engineering** (Année universitaire : 2024-2025). Il s’inscrit dans les projets pratiques visant à maîtriser Symfony, l’architecture MVC, les formulaires dynamiques et la gestion des entités avec Doctrine ORM.

---

## Description du Projet

Ce projet Symfony est une application web complète de **gestion du transport international et de livraison de colis**. Il couvre la gestion de multiples entités et permet aux utilisateurs de suivre leurs opérations logistiques via une interface moderne basée sur Twig.

### Objectifs :
- Créer une solution web de suivi logistique.
- Automatiser les traitements douaniers, factures et réclamations.
- Permettre un usage fluide par les agents, clients et gestionnaires via l’interface web.

### Fonctionnalités principales :
-  Réservation et gestion des **billets**
-  Suivi des **livraisons** en temps réel
-  Contrôle **douanier** automatisé
-  Gestion des **réclamations** et **réponses**
-  Émission de **factures** avec remises
-  Système de **réduction** pour fidélisation
-  Interface de **support / assistance**

---

##  Table des Matières

- [Contexte Académique](#contexte-académique)
- [Description du Projet](#description-du-projet)
- [Technologies Utilisées](#technologies-utilisées)
- [Structure du Projet](#structure-du-projet)
- [Installation](#installation)
- [Utilisation](#utilisation)
- [Mots-clés & Topics](#mots-clés--topics)
- [Contribution](#contribution)
- [Licence](#licence)
- [Remerciements](#remerciements)

---

##  Technologies Utilisées

-  **PHP 8.2+**
-  **Symfony 6.4**
-  **Doctrine ORM**
-  **Twig**
-  **Composer**
- **Bootstrap 5** (via Webpack Encore)
-  **VS Code / PhpStorm**

---

##  Structure du Projet

```plaintext
config/
public/
src/
├── Controller/
├── Entity/
├── Repository/
templates/
├── base.html.twig
├── livraison/
└── billet/
translations/
var/
vendor/
README.md
composer.json
```
---

##  Installation

1. **Cloner le repository :**
```bash
git clone https://github.com/koussaybenayed/projet_symfony.git
cd projet_symfony
```

2. **Installer les dépendances :**
```bash
composer install
```

3. **Configurer la base de données :**
- Créer un fichier `.env.local` avec votre configuration :
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/navifly_db"
```
- Puis exécuter :
```bash
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

4. **Lancer le serveur :**
```bash
symfony server:start
```

---

##  Utilisation

- Interface d’accueil avec tableau de bord
- Formulaires de réservation de **billets**
- Gestion des **livraisons** : ajout, statut, suivi
- Contrôle **douanier** avec autorisation/rejet
- Réclamations clients et interface de **réponse**
- Calcul et visualisation des **factures** et **réductions**
- Zone de contact / **support**

---

##  Mots-clés & Topics

**Topics à utiliser sur GitHub** :
```text
symfony, transport, livraison, esprit-school-of-engineering, controle-douanier, web-app, doctrine, twig, university-project, php
```

**Mots-clés dans ce README** :
- Symfony
- Livraison
- Doctrine
- Contrôle douanier
- Web application
- Réclamations
- Esprit School of Engineering

---

##  Contribution

1. **Fork** le projet
2. Crée une branche : `git checkout -b feature-ma-fonction`
3. Commit : `git commit -m "Ajout nouvelle fonctionnalité"`
4. Push : `git push origin feature-ma-fonction`
5. Ouvrir une **Pull Request**

---

##  Licence

Ce projet est distribué sous la licence **MIT**.  
Libre d’utiliser, modifier, redistribuer sous les conditions définies.

---

##  Remerciements

Ce projet a été encadré par l’équipe pédagogique de **Esprit School of Engineering**.  
Merci aux encadrants pour leur accompagnement et leurs conseils tout au long de ce projet académique.

---
