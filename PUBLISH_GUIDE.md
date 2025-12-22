# 📤 Publication Guide - PHP Shomer

Guide étape par étape pour publier PHP Shomer sur GitHub et Packagist.

## 📋 Pré-requis

- [ ] Compte GitHub
- [ ] Compte Packagist (packagist.org)
- [ ] Git installé localement
- [ ] Composer installé

## 🚀 Étape 1 : Créer le dépôt GitHub

### 1.1 Créer un nouveau repository sur GitHub

1. Allez sur https://github.com/new
2. **Repository name**: `php-shomer`
3. **Description**: `🛡️ SQL Query Guardian (שומר) - Runtime validation and security for PHP development`
4. **Visibilité**: Public
5. **N'ajoutez PAS** de README, .gitignore, ou LICENSE (on les a déjà)
6. Cliquez sur "Create repository"

### 1.2 Initialiser Git localement

```bash
cd php-shomer

# Initialiser git
git init

# Ajouter tous les fichiers
git add .

# Premier commit
git commit -m "🎉 Initial release - Shomer v1.0.0"

# Ajouter le remote GitHub
git remote add origin https://github.com/VOTRE-USERNAME/php-shomer.git

# Pousser sur GitHub
git branch -M main
git push -u origin main
```

## 🏷️ Étape 2 : Créer une Release

### 2.1 Créer un tag

```bash
# Créer le tag v1.0.0
git tag -a v1.0.0 -m "🛡️ Shomer v1.0.0 - Initial Release"

# Pousser le tag
git push origin v1.0.0
```

### 2.2 Créer la release sur GitHub

1. Sur GitHub, allez dans **Releases** → **Create a new release**
2. **Choose a tag**: Sélectionnez `v1.0.0`
3. **Release title**: `🛡️ Shomer v1.0.0 - Initial Release`
4. **Description**:

```markdown
# 🛡️ PHP Shomer (שומר) v1.0.0

**Your SQL Query Guardian** - First stable release!

## ✨ Features

- ✅ Runtime SQL query validation for development
- ✅ Prepared statement support (PDO & MySQLi)
- ✅ SQL injection pattern detection
- ✅ Syntax and security validation
- ✅ Zero performance impact in production
- ✅ Email notifications for critical errors
- ✅ Educational error messages

## 📦 Installation

```bash
composer require votre-username/php-shomer
```

## 🚀 Quick Start

```php
use Shomer\QueryValidator;

$query = [
    'sql' => "SELECT * FROM users WHERE id = ?",
    'params' => [123]
];

$report = QueryValidator::validate($query, true);
```

## 📚 Documentation

- [README](https://github.com/votre-username/php-shomer#readme)
- [Quick Start Guide](https://github.com/votre-username/php-shomer/blob/main/QUICKSTART.md)
- [Examples](https://github.com/votre-username/php-shomer/tree/main/examples)

## 🙏 Thank You

Shomer (שומר) means "Guardian" in Hebrew. Thank you for trusting Shomer to guard your SQL queries!

---

**Shomer: Because your database deserves a guardian.** 🛡️
```

5. Cliquez sur **Publish release**

## 📦 Étape 3 : Publier sur Packagist

### 3.1 Créer un compte Packagist

1. Allez sur https://packagist.org
2. Cliquez sur "Sign in with GitHub"
3. Autorisez l'application

### 3.2 Soumettre le package

1. Une fois connecté, cliquez sur **Submit**
2. Entrez l'URL de votre repo: `https://github.com/VOTRE-USERNAME/php-shomer`
3. Cliquez sur **Check**
4. Vérifiez les informations
5. Cliquez sur **Submit**

### 3.3 Configurer l'auto-update (IMPORTANT)

Pour que Packagist se mette à jour automatiquement :

1. Sur Packagist, allez dans votre package
2. Cliquez sur l'onglet **Settings**
3. Notez l'URL du webhook
4. Sur GitHub, allez dans **Settings** → **Webhooks** → **Add webhook**
5. Collez l'URL du webhook Packagist
6. **Content type**: `application/json`
7. **Events**: Sélectionnez "Just the push event"
8. Cliquez sur **Add webhook**

✅ Maintenant, chaque fois que vous poussez un nouveau tag, Packagist se met à jour automatiquement !

## 🎨 Étape 4 : Améliorer le dépôt GitHub

### 4.1 Ajouter des Topics

Sur GitHub, dans votre repo :
1. Cliquez sur ⚙️ à côté de "About"
2. Ajoutez ces topics:
   - `php`
   - `sql`
   - `security`
   - `validation`
   - `prepared-statements`
   - `sql-injection`
   - `debugging`
   - `development-tools`
   - `guardian`

### 4.2 Configurer la description

Dans "About", ajoutez :
- **Description**: `🛡️ SQL Query Guardian (שומר) - Runtime validation and security for PHP`
- **Website**: Votre site web ou lien vers la doc
- **Topics**: (ajoutés ci-dessus)

### 4.3 Activer les Issues et Discussions

1. **Settings** → **General**
2. Cochez **Issues**
3. Cochez **Discussions**

### 4.4 Créer un GitHub Wiki (optionnel)

Pour une documentation plus étendue :
1. **Wiki** → **Create the first page**
2. Ajoutez des guides détaillés

## 📢 Étape 5 : Promotion

### 5.1 Annoncer sur les réseaux sociaux

**Twitter/X**:
```
🛡️ Introducing PHP Shomer (שומר) - Your SQL Query Guardian!

✅ Runtime query validation
✅ Prepared statement support
✅ SQL injection detection
✅ Zero production overhead

Perfect for PHP developers who want to catch SQL errors during development!

github.com/votre-username/php-shomer

#PHP #Security #OpenSource
```

**Reddit** (r/PHP):
```markdown
Title: [Release] PHP Shomer - SQL Query Guardian for Development

I've just released PHP Shomer (שומר), a runtime SQL query validator for PHP development.

It helps catch SQL errors, injection patterns, and encourages prepared statements - all during development with zero production overhead.

Key features:
- Validates prepared statements
- Detects parameter mismatches
- Catches injection patterns
- Educational error messages
- Simple on/off switch

GitHub: github.com/votre-username/php-shomer

Feedback welcome!
```

**Dev.to** - Écrire un article complet:
```markdown
Title: Introducing Shomer: A Guardian for Your SQL Queries

[Article détaillé expliquant le concept, l'utilisation, etc.]
```

### 5.2 Soumettre aux newsletters

- **PHP Weekly**: https://www.phpweekly.com/
- **PHP Annotated Monthly**: Mentionnez sur Twitter avec @phpstorm

### 5.3 Annoncer sur les forums

- **Stack Overflow**: Créer un tag wiki
- **SitePoint Forums**: Post dans la section PHP
- **PHP.net User Notes**: Mentionner dans les docs pertinentes

## 📊 Étape 6 : Suivi et maintenance

### 6.1 Badges pour le README

Ajoutez ces badges en haut du README :

```markdown
[![Latest Version](https://img.shields.io/packagist/v/votre-username/php-shomer.svg)](https://packagist.org/packages/votre-username/php-shomer)
[![Total Downloads](https://img.shields.io/packagist/dt/votre-username/php-shomer.svg)](https://packagist.org/packages/votre-username/php-shomer)
[![License](https://img.shields.io/packagist/l/votre-username/php-shomer.svg)](https://github.com/votre-username/php-shomer/blob/main/LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
```

### 6.2 Répondre aux issues

- Répondez rapidement aux issues
- Soyez accueillant avec les nouveaux contributeurs
- Marquez les "good first issue"

### 6.3 Releases régulières

Quand vous ajoutez des features :

```bash
# Modifier les fichiers
git add .
git commit -m "Add PostgreSQL support"
git push

# Créer un nouveau tag
git tag -a v1.1.0 -m "Add PostgreSQL support"
git push origin v1.1.0

# Créer la release sur GitHub
```

## ✅ Checklist finale

Avant la publication, vérifiez :

- [ ] Le `composer.json` contient votre vrai username
- [ ] Le README contient vos vraies infos de contact
- [ ] La LICENSE contient votre vrai nom
- [ ] Tous les liens fonctionnent
- [ ] Les exemples sont testés
- [ ] Le code respecte PSR-12
- [ ] Les messages d'erreur sont clairs
- [ ] La documentation est complète

## 🎉 Félicitations !

Votre package est maintenant publié ! 🎊

### Prochaines étapes :

1. Surveiller les issues et PR
2. Améliorer la documentation basée sur les retours
3. Ajouter des features demandées
4. Maintenir une bonne communication avec la communauté
5. Célébrer chaque milestone (100 stars, 1000 downloads, etc.)

---

**Shomer (שומר)** - Protecting your queries, one validation at a time. 🛡️
