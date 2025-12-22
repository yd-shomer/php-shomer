# 🚀 Quick Start Guide - PHP Shomer (שומר)

Get started with Shomer in 5 minutes!

## 📦 Installation

```bash
composer require votre-username/php-shomer
```

## ⚡ Basic Usage

### Step 1: Enable Shomer

```php
<?php
require 'vendor/autoload.php';

use Shomer\QueryValidator;

// Enable in development
define('SHOMER_ENABLED', true);
```

### Step 2: Validate Your Queries

```php
// Validate a prepared statement
$query = [
    'sql' => "SELECT * FROM users WHERE id = ?",
    'params' => [123]
];

$report = QueryValidator::validate($query, true);

if ($report['status'] === 'error') {
    print_r($report['erreurs']);
}
```

### Step 3: Disable in Production

```php
// In production config
define('SHOMER_ENABLED', false); // Zero overhead!
```

## 📚 Common Use Cases

### Validating INSERT Queries

```php
$query = [
    'sql' => "INSERT INTO users (name, email) VALUES (?, ?)",
    'params' => ['John', 'john@example.com']
];

$report = QueryValidator::validate($query, true, true);
```

### Detecting Missing WHERE Clauses

```php
$query = [
    'sql' => "DELETE FROM users", // ❌ No WHERE clause!
    'params' => []
];

$report = QueryValidator::validate($query, true);
// Will detect: "DELETE without WHERE clause"
```

### Catching Parameter Mismatches

```php
$query = [
    'sql' => "INSERT INTO users (name, email, age) VALUES (?, ?, ?)",
    'params' => ['John', 'john@example.com'] // ❌ Missing age!
];

$report = QueryValidator::validate($query, true);
// Will detect: "Placeholder count differs from parameter count"
```

### Quick Validation

```php
// Just need a boolean result?
if (!QueryValidator::isValid($query)) {
    throw new Exception('Invalid query!');
}
```

## 🎛️ Configuration Options

### Email Notifications

```php
// Method 1: Use native mail()
$report = QueryValidator::validate($query, true, true, 1);

// Method 2: Use custom function
function sendAlert($body, $report) {
    // Your email logic here
}
$report = QueryValidator::validate($query, true, true, 'sendAlert');
```

### Verbose Mode

```php
// Get detailed analysis
$report = QueryValidator::validate($query, true, true); // verbose = true

// Get only errors
$report = QueryValidator::validate($query, true, false); // verbose = false
```

## 🔧 Integration Examples

### With PDO

```php
use Shomer\QueryValidator;

$pdo = new PDO($dsn, $user, $pass);

$sql = "SELECT * FROM users WHERE email = :email";
$params = ['email' => 'user@example.com'];

// Validate before executing
$report = QueryValidator::validate([
    'sql' => $sql,
    'params' => $params
], true);

if ($report['status'] === 'success') {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}
```

### With MySQLi

```php
use Shomer\QueryValidator;

$mysqli = new mysqli($host, $user, $pass, $db);

$sql = "INSERT INTO users (name, email) VALUES (?, ?)";
$params = ['John Doe', 'john@example.com'];

// Validate first
$report = QueryValidator::validate([
    'sql' => $sql,
    'params' => $params
], true);

if ($report['status'] === 'success') {
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ss', ...$params);
    $stmt->execute();
}
```

### In a Development Wrapper

```php
class DB {
    private $pdo;
    
    public function query($sql, $params = []) {
        if (defined('SHOMER_ENABLED') && SHOMER_ENABLED) {
            $report = QueryValidator::validate([
                'sql' => $sql,
                'params' => $params
            ], true);
            
            if ($report['status'] === 'error') {
                error_log('Shomer detected errors: ' . json_encode($report['erreurs']));
            }
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
```

## 🎯 Best Practices

### ✅ DO:
- Use Shomer during development
- Always use prepared statements
- Review Shomer warnings and fix issues
- Disable Shomer in production
- Keep queries simple and readable

### ❌ DON'T:
- Don't use raw SQL with user input
- Don't ignore Shomer warnings
- Don't leave Shomer enabled in production (performance)
- Don't rely only on Shomer for security

## 🐛 Troubleshooting

### "Class 'Shomer\QueryValidator' not found"

Make sure you've run `composer install` and included the autoloader:
```php
require 'vendor/autoload.php';
```

### Validation is too slow in development

Use verbose mode (`false`) to skip detailed analysis:
```php
$report = QueryValidator::validate($query, true, false);
```

### Want to test without installing?

Download the examples and run them:
```bash
git clone https://github.com/votre-username/php-shomer
cd php-shomer
composer install
php examples/basic_usage.php
```

## 📖 Next Steps

- Read the [full documentation](README.md)
- Check out [examples](examples/)
- Learn about [contributing](CONTRIBUTING.md)
- Review the [changelog](CHANGELOG.md)

## 💬 Get Help

- 🐛 [Report issues](https://github.com/votre-username/php-shomer/issues)
- 💬 [Ask questions](https://github.com/votre-username/php-shomer/discussions)
- 📧 Email: your-email@example.com

---

**Shomer (שומר)** - Because your database deserves a guardian. 🛡️
