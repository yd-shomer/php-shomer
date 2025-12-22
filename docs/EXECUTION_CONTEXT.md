# 📍 Execution Context - Auto-Debugging Feature

One of Shomer's most powerful features is **automatic execution context capture**. Every validation report includes detailed information about where the query was executed, making debugging instant and effortless.

## 🎯 The Problem

Traditional debugging workflow:
1. ❌ SQL error occurs in production/development
2. ❌ Error message: "SQL syntax error near '...'"
3. ❌ You search through 50 files to find where this query is
4. ❌ You add `var_dump()` or `error_log()` to track it down
5. ❌ 30 minutes wasted

## ✨ The Shomer Solution

With Shomer's execution context:
1. ✅ SQL error detected
2. ✅ Email arrives with **exact file, line, and function**
3. ✅ You open the file, go to the line, fix the issue
4. ✅ 30 seconds, done!

## 📊 What Gets Captured

### File Information
- **Absolute path**: `/var/www/html/src/Services/UserService.php`
- **Relative path**: `./src/Services/UserService.php` (easier to read)
- **Line number**: `42`

### Function/Method Information
- **Function name**: `validateUser`
- **Class name**: `App\Services\UserService` (if it's a method)
- **Method type**: `->` (instance) or `::` (static)

### Web Context (HTTP Requests)
- **Full URL**: `https://example.com/api/login`
- **HTTP Method**: `POST`, `GET`, `PUT`, `DELETE`, etc.
- **Script name**: `/var/www/html/public/index.php`

### CLI Context (Command Line)
- **Script path**: `/path/to/your/script.php`
- **Method**: `CLI`

## 📧 In Email Reports

When Shomer sends an email alert, the context appears at the top:

```
╔════════════════════════════════════════════════════╗
║  🛡️  SHOMER (שומר) - SQL QUERY GUARDIAN ALERT  ║
╚════════════════════════════════════════════════════╝

Date/Time: 2025-01-15 14:30:25
Type: Requête préparée
Status: error
Errors: 2
Warnings: 0

EXECUTION CONTEXT:
──────────────────────────────────────────────────
📄 File: ./src/Services/UserService.php
📍 Line: 42
🔧 Method: App\Services\UserService->validateLogin()
🌐 URL: https://example.com/api/login
📝 Method: POST
──────────────────────────────────────────────────

QUERY:
──────────────────────────────────────────────────
INSERT INTO users (name, email, age) VALUES (?, ?, ?)
──────────────────────────────────────────────────

PARAMETERS:
  [0] = 'John Doe'
  [1] = 'john@example.com'

❌ ERRORS DETECTED:
  • ERREUR CRITIQUE : Nombre de placeholders (3) différent 
    du nombre de paramètres (2)
```

**You immediately know:**
- Which file: `./src/Services/UserService.php`
- Which line: `42`
- Which method: `UserService->validateLogin()`
- Which URL triggered it: `https://example.com/api/login`

## 🔍 How It Works

Shomer uses PHP's `debug_backtrace()` to capture the execution stack:

```php
// Somewhere in your code
class UserService {
    public function validateLogin($username, $password) {
        $query = [
            'sql' => "SELECT * FROM users WHERE username = ?",
            'params' => [$username]
        ];
        
        // Shomer captures THIS location automatically
        $report = QueryValidator::validate($query, true);
    }
}
```

When the validation runs, Shomer:
1. Analyzes the call stack
2. Finds the first call **outside** of Shomer's namespace
3. Extracts file, line, function/class information
4. Adds URL/script information from `$_SERVER`
5. Includes everything in the report

## 💻 Code Examples

### Example 1: Function Context

```php
function processUserData($userId) {
    $query = [
        'sql' => "DELETE FROM users WHERE id = ?",
        'params' => [$userId]
    ];
    
    $report = QueryValidator::validate($query, true);
    // Context will show: Function: processUserData()
}
```

### Example 2: Class Method Context

```php
class OrderRepository {
    public function deleteOrder($orderId) {
        $query = [
            'sql' => "DELETE FROM orders WHERE id = ?",
            'params' => [$orderId]
        ];
        
        $report = QueryValidator::validate($query, true);
        // Context will show: Method: OrderRepository->deleteOrder()
    }
}
```

### Example 3: Static Method Context

```php
class DatabaseHelper {
    public static function runQuery($sql, $params) {
        $report = QueryValidator::validate([
            'sql' => $sql,
            'params' => $params
        ], true);
        // Context will show: Method: DatabaseHelper::runQuery()
    }
}
```

## 🌐 Web vs CLI Detection

Shomer automatically detects the execution environment:

### Web Request
```php
// Accessing: https://example.com/products/create
// via POST method

$report['context'] = [
    'url' => 'https://example.com/products/create',
    'method' => 'POST',
    'script_name' => '/var/www/public/index.php',
    // ... file, line, function info
];
```

### CLI Script
```php
// Running: php /path/to/cleanup.php

$report['context'] = [
    'url' => null,
    'method' => 'CLI',
    'script_name' => '/path/to/cleanup.php',
    // ... file, line, function info
];
```

## 📱 Accessing Context in Code

```php
$report = QueryValidator::validate($query, true);

// Access context
$context = $report['context'];

// Log it
error_log("Error in {$context['file']} at line {$context['line']}");

// Display it
echo "Error occurred in: " . $context['file_relative'] . ":" . $context['line'];

// Send to monitoring service
sendToMonitoring([
    'file' => $context['file'],
    'line' => $context['line'],
    'function' => $context['function'],
    'url' => $context['url']
]);
```

## 🎨 HTML Reports

When using `ValidationReport::toHtml()`, the context appears in an expandable section:

```php
$html = ValidationReport::toHtml($report);
echo $html;
```

Output:
```html
<details open>
  <summary><strong>📍 Execution Context</strong></summary>
  <ul>
    <li><strong>File:</strong> <code>./src/UserService.php</code></li>
    <li><strong>Line:</strong> 42</li>
    <li><strong>Method:</strong> <code>UserService->validate()</code></li>
    <li><strong>URL:</strong> https://example.com/login</li>
    <li><strong>Method:</strong> POST</li>
  </ul>
</details>
```

## ⚙️ Configuration

Execution context capture is **always enabled** when Shomer is active. There's no configuration needed - it just works!

To disable Shomer entirely (and thus context capture):
```php
define('SHOMER_ENABLED', false);
```

## 🚀 Performance Impact

Context capture is **extremely fast**:
- Uses `debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10)` - limited to 10 frames
- Only captures when validation runs (development only)
- No overhead in production when Shomer is disabled
- Adds ~0.0001 seconds to validation time

## 🎯 Best Practices

### ✅ DO:
- Keep context information in your error logs
- Include context when reporting issues to your team
- Use context to create monitoring dashboards
- Review context patterns to find problematic code areas

### ❌ DON'T:
- Don't send context to public error pages (security risk)
- Don't log context in production unless properly secured
- Don't expose file paths to end users

## 🔒 Security Considerations

The execution context includes **sensitive information** (file paths, class names):

1. **Never display to end users** - only for developers
2. **Sanitize in logs** if logs might be exposed
3. **Use relative paths** in emails/reports (Shomer does this automatically)
4. **Secure your email** - use encrypted transport for alerts

## 📖 Real-World Example

```php
// Your application code
class ProductController {
    public function store(Request $request) {
        $query = [
            'sql' => "INSERT INTO products (name, price, stock) VALUES (?, ?, ?)",
            'params' => [
                $request->input('name'),
                $request->input('price')
                // ❌ Missing 'stock' parameter!
            ]
        ];
        
        $report = QueryValidator::validate($query, true, true, 1);
        
        // If error, you get email:
        // File: ./app/Http/Controllers/ProductController.php
        // Line: 127
        // Method: ProductController->store()
        // URL: https://shop.example.com/admin/products
        // Method: POST
        //
        // Error: Nombre de placeholders (3) différent du nombre de paramètres (2)
        
        // You immediately know:
        // - Which controller: ProductController
        // - Which method: store()
        // - Which line: 127
        // - Which URL: /admin/products
        // 
        // FIX: Add the missing 'stock' parameter on line 127!
    }
}
```

## 🎓 Summary

Execution context makes debugging **effortless**:
- ✅ No searching through files
- ✅ No adding debug statements
- ✅ No guessing where the error is
- ✅ Instant, precise location
- ✅ Ready-to-use information

**Shomer captures it all automatically, so you can focus on fixing, not finding.**

---

**Shomer (שומר)** - Because your database deserves a guardian who tells you exactly where the problem is! 🛡️
