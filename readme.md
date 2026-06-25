# QualityCheck Packman (QCP)

> Application web de gestion des certificats de conformité qualité automobile — Projet DWWM Niveau 5

![Symfony](https://img.shields.io/badge/Symfony-7.4-black?logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?logo=mysql)
![Docker](https://img.shields.io/badge/Docker-27-blue?logo=docker)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?logo=bootstrap)

---

## Présentation

QualityCheck Packman est une application web de gestion des contrôles qualité destinée aux analystes du service **PROTO E-TECH DRIVE de Renault**. Elle numérise et centralise les certificats de conformité des supports de validation (SV), aujourd'hui gérés manuellement via des fichiers Excel partagés.

L'application permet de :
- Saisir et archiver les checklists de contrôle SO/C/NC avant livraison
- Associer des photos en cas de non-conformité
- Gérer les utilisateurs, catégories, types et critères depuis un espace admin

---

## Stack technique

| Catégorie | Technologie | Version |
|---|---|---|
| Framework back-end | Symfony | 7.4 |
| Langage | PHP | 8.2 |
| ORM | Doctrine | Via Symfony |
| Base de données | MySQL | 8.0 |
| Templating | Twig | Via Symfony |
| Framework front-end | Bootstrap + Bootswatch Solar | 5.3 |
| Conteneurisation | Docker / Docker Compose | 27 / v2 |
| Serveur web | Nginx | Via Docker |
| Mail (dev) | Mailhog | Via Docker |
| Versionnage | Git / GitHub | 2.x |
| Gestion de projet | Jira (Kanban) | Cloud |

---

## Fonctionnalités

### Analyste Qualité
- Connexion sécurisée par matricule
- Dashboard avec navigation par catégorie d'organe (Moteur thermique, GMPE, E-Motor, Module, Batterie)
- Création de certificat SO/C/NC avec cotation dynamique par critère
- Upload de photos en cas de non-conformité (jusqu'à 20 photos par critère)
- Consultation du détail et de l'historique des certificats

### Administrateur
- Gestion complète des utilisateurs (CRUD + email automatique de création de compte)
- Gestion des catégories, types d'organes et critères de contrôle
- Gestion des certificats
- Accès à l'ensemble des fonctionnalités analyste

---

## Modèle de données

Le modèle s'articule autour de **7 entités** conçues avec la méthode Merise :

```
User ──────────────── Certificate
                           │
Category                   │
   └── CategoryType ───────┘
           │
           └── Criterion ── Evaluation ── Image
```

---

## Installation

### Prérequis

- [Docker](https://www.docker.com/) et Docker Compose installés
- [Git](https://git-scm.com/) installé

### Lancer le projet

```bash
# 1. Cloner le dépôt
git clone https://github.com/cdhinho27370/qualitycheck-packman.git
cd qualitycheck-packman

# 2. Copier le fichier d'environnement
cp .env .env.local
# Modifier .env.local si nécessaire (BDD, mailer…)

# 3. Lancer les conteneurs Docker
docker compose up -d

# 4. Installer les dépendances PHP
docker exec qualitycheck_php composer install

# 5. Créer la base de données
docker exec qualitycheck_php php bin/console doctrine:database:create

# 6. Lancer les migrations
docker exec qualitycheck_php php bin/console doctrine:migrations:migrate

# 7. Charger les données de test
docker exec qualitycheck_php php bin/console doctrine:fixtures:load
```

### Accès

| Service | URL |
|---|---|
| Application | http://localhost:8080 |
| Mailhog (emails) | http://localhost:8025 |

### Comptes de test

| Rôle | Matricule | Mot de passe |
|---|---|---|
| Administrateur | A670997 | *(défini via email de création)* |
| Analyste Qualité | A670867 | *(défini via email de création)* |

---

## Structure du projet

```
src/
├── Controller/
│   ├── Admin/
│   │   ├── UserController.php
│   │   └── CategoryController.php   # Catégories + Types + Critères
│   ├── CertificateController.php    # Analyste + Admin certificats
│   ├── DashboardController.php
│   └── ResetPasswordController.php
├── Service/
│   ├── Admin/
│   │   ├── UserHandler.php
│   │   └── CategoryHandler.php
│   └── CertificateHandler.php
├── Entity/
│   ├── User.php
│   ├── Category.php
│   ├── CategoryType.php
│   ├── Certificate.php
│   ├── Criterion.php
│   ├── Evaluation.php
│   └── Image.php
└── Form/
    ├── Admin/
    │   ├── UserFormType.php
    │   ├── CategoryFormType.php
    │   ├── CategoryTypeFormType.php
    │   └── CriterionFormType.php
    ├── CertificateFormType.php
    ├── ChangePasswordFormType.php
    └── ResetPasswordRequestFormType.php

templates/
├── base.html.twig
├── dashboard/
├── certificate/
├── admin/
│   ├── user/
│   ├── category/
│   ├── criterion/
│   └── certificate/
└── reset_password/

assets/
├── js/
│   └── certificate.js     # Gestion SO/C/NC + upload photos NC
└── styles/
    └── app.css
```

---

## Sécurité

| Vulnérabilité | Protection |
|---|---|
| Injection SQL | Doctrine ORM avec QueryBuilder paramétré |
| XSS | Échappement automatique Twig `{{ variable }}` |
| CSRF | Token automatique dans chaque formulaire Symfony |
| Force brute | `login_throttling` Symfony activé |
| Upload malveillant | Vérification MIME réelle + nom aléatoire `bin2hex(random_bytes(16))` |
| Authentification | Bcrypt via `UserPasswordHasherInterface` |
| Autorisations | `#[IsGranted('ROLE_ADMIN')]` sur toutes les routes admin |

---

## Roadmap v2

- [ ] Export PDF du certificat finalisé
- [ ] Prise de photo directe depuis tablette (`capture="environment"`)
- [ ] Tests unitaires PHPUnit
- [ ] CI/CD GitHub Actions

---

## Auteur

**Cyril David** — Matricule A670997  
Titre Professionnel Développeur Web et Web Mobile (DWWM) — Niveau 5  
Session juillet 2026
