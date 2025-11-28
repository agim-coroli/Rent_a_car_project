# 🚗 Rent A Car - Projet Expérimental

> **Projet d'apprentissage** | Architecture MVC | PHP Orienté Objet | API REST

## 🎯 Objectif

Ce projet est un **laboratoire d'apprentissage** où j'expérimente avec des concepts concrets de développement web. L'objectif n'est pas de créer une application finale, mais de **progresser en pratiquant** sur un cas d'usage réel : un système de location de véhicules.

## 🛠️ Stack Technique

- **Backend** : PHP 8+ (Orienté Objet)
- **Architecture** : MVC custom avec séparation des responsabilités
- **Base de données** : MySQL/MariaDB avec PDO
- **API** : RESTful API avec format JSON standardisé
- **Email** : PHPMailer pour les notifications
- **Gestion des dépendances** : Composer

## 📐 Architecture

```
src/
├── controller/     # Routage et logique HTTP
├── model/          # Entités, Managers, Mappings
├── service/        # Couche métier (UserService, EmailService)
└── view/           # Templates PHP
```

**Principes appliqués** :
- Séparation Model/View/Controller
- Service Layer pour la logique métier
- Repository Pattern (Managers)
- Exception handling personnalisé (traductions FR)
- Validation des données dans les setters

## 🚀 Fonctionnalités Implémentées

- ✅ Gestion des utilisateurs (CRUD complet)
- ✅ Authentification admin
- ✅ API REST pour les utilisateurs
- ✅ Dashboard admin avec génération de données de test
- ✅ Gestion des exceptions avec traductions françaises
- ✅ Service d'email (PHPMailer)

## 📚 Ce que j'apprends ici

- **Architecture propre** : Comment structurer un projet PHP maintenable
- **Séparation des responsabilités** : Service Layer, Repository Pattern
- **Gestion d'erreurs** : Exceptions personnalisées, traductions
- **API REST** : Design de endpoints, format de réponse standardisé
- **Sécurité** : Hashage de mots de passe, validation des données
- **Bonnes pratiques** : Code réutilisable, testable, documenté

## ⚠️ État du Projet

**En cours de développement** - Ce projet évolue au fur et à mesure de mes apprentissages.

Certaines fonctionnalités sont incomplètes ou expérimentales. C'est volontaire : chaque commit représente une nouvelle compréhension ou un nouveau concept testé.

## 🎓 Pourquoi ce projet ?

Plutôt que de suivre uniquement des tutoriels, je préfère **construire en apprenant**. Ce projet me permet de :
- Confronter la théorie à la pratique
- Rencontrer de vrais problèmes et les résoudre
- Comprendre les choix d'architecture
- Progresser sur un cas concret

## 📝 Note pour les Recruteurs

Ce projet reflète ma **démarche d'apprentissage active**. Chaque ligne de code est le résultat d'une réflexion, d'une recherche, ou d'un test. 

Si vous cherchez quelqu'un qui :
- ✅ Apprend rapidement
- ✅ Cherche à comprendre le "pourquoi" avant le "comment"
- ✅ Code avec intention et réflexion
- ✅ N'a pas peur de refactoriser pour améliorer

Alors ce projet vous donnera une bonne idée de ma façon de travailler. 🚀

---

**Dernière mise à jour** : En cours de développement actif


