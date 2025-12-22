# 💡 Secure Query Suggestions

One of Shomer's most educational features is **secure query suggestions**. In verbose mode, Shomer doesn't just tell you what's wrong—it shows you exactly how to fix it!

## 🎯 Why Suggestions?

Learning secure SQL can be challenging. Shomer acts as both a **validator** and a **teacher**, providing:

- ✅ **Corrected SQL queries**
- ✅ **Ready-to-use PHP code**
- ✅ **Clear explanations** of why the suggestion is better

## 🔧 How It Works

Enable verbose mode and Shomer will analyze your query. If it finds issues, it generates tailored suggestions:

```php
$report = QueryValidator::validate($query, true, true); // verbose = true

if ($report['suggestion']) {
    echo $report['suggestion']['query'];       // Corrected SQL
    echo $report['suggestion']['code'];        // PHP example
    echo $report['suggestion']['explanation']; // Why this is better
}
```

## 📚 Types of Suggestions

### 1. Non-Prepared Query → Prepared Statement

**Your Code:**
```php
$query = "SELECT * FROM users WHERE email = 'user@example.com'";
```

**Shomer's Suggestion:**
```
💡 SECURE QUERY SUGGESTION:

SQL:
SELECT * FROM table WHERE column = ?

PHP Code Example:
$stmt = $pdo->prepare("SELECT * FROM table WHERE column = ?");
$stmt->execute([$value]);
$result = $stmt->fetchAll();

Explanation:
Convert to a prepared statement to prevent SQL injection. Replace values 
with placeholders (?) and pass them as parameters.
```

---

### 2. Parameter Count Mismatch

**Your Code:**
```php
$query = [
    'sql' => "INSERT INTO users (name, email, age) VALUES (?, ?, ?)",
    'params' => ['John', 'john@example.com'] // Missing age!
];
```

**Shomer's Suggestion:**
```
💡 SECURE QUERY SUGGESTION:

PHP Code Example:
$query = [
    'sql' => "INSERT INTO users (name, email, age) VALUES (?, ?, ?)",
    'params' => ['John', 'john@example.com', 'missing_value']
];

Explanation:
You have 3 placeholders but only 2 parameters. Add 1 more parameter(s) 
to the array.
```

---

### 3. DELETE Without WHERE Clause

**Your Code:**
```php
$query = [
    'sql' => "DELETE FROM sessions",
    'params' => []
];
```

**Shomer's Suggestion:**
```
💡 SECURE QUERY SUGGESTION:

SQL:
DELETE FROM table WHERE id = ?

PHP Code Example:
// ALWAYS use a WHERE clause with DELETE
$stmt = $pdo->prepare("DELETE FROM table WHERE id = ?");
$stmt->execute([$id]);

// Or for multiple conditions:
$stmt = $pdo->prepare("DELETE FROM table WHERE status = ? AND created_at < ?");
$stmt->execute([$status, $date]);

Explanation:
CRITICAL: DELETE without WHERE clause will affect ALL rows in the table! 
Always specify which rows to DELETE using a WHERE clause with appropriate 
conditions.
```

---

### 4. UPDATE Without WHERE Clause

**Your Code:**
```php
$query = [
    'sql' => "UPDATE users SET status = ?",
    'params' => ['inactive']
];
```

**Shomer's Suggestion:**
```
💡 SECURE QUERY SUGGESTION:

SQL:
UPDATE FROM table WHERE id = ?

PHP Code Example:
// ALWAYS use a WHERE clause with UPDATE
$stmt = $pdo->prepare("UPDATE FROM table WHERE id = ?");
$stmt->execute([$id]);

Explanation:
CRITICAL: UPDATE without WHERE clause will affect ALL rows in the table! 
Always specify which rows to UPDATE using a WHERE clause with appropriate 
conditions.
```

---

### 5. SELECT * Usage

**Your Code:**
```php
$query = [
    'sql' => "SELECT * FROM products WHERE category = ?",
    'params' => ['electronics']
];
```

**Shomer's Suggestion:**
```
💡 SECURE QUERY SUGGESTION:

SQL:
SELECT id, name, email, created_at FROM users WHERE active = ?

PHP Code Example:
// Specify only the columns you need
$stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE active = ?");
$stmt->execute([1]);

// Benefits:
// 1. Better performance (less data transferred)
// 2. More maintainable (explicit dependencies)
// 3. Safer (won't break if table structure changes)

Explanation:
Avoid SELECT * in production code. Explicitly list the columns you need. 
This improves performance, makes your code more maintainable, and prevents 
issues when table structure changes.
```

---

### 6. Hardcoded Values in Prepared Statements

**Your Code:**
```php
$query = [
    'sql' => "INSERT INTO logs (message, level) VALUES (?, 'ERROR')",
    'params' => ['Connection failed']
];
```

**Shomer's Suggestion:**
```
💡 SECURE QUERY SUGGESTION:

SQL:
INSERT INTO logs (message, level, user_id) VALUES (?, ?, ?)

PHP Code Example:
// WRONG: Hardcoded values in prepared statement
// $sql = "INSERT INTO logs (message, level) VALUES (?, 'ERROR')";

// CORRECT: Use placeholders for all values
$sql = "INSERT INTO logs (message, level, user_id) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$message, $level, $userId]);

Explanation:
Even in prepared statements, avoid hardcoding values directly in the SQL. 
Use placeholders for all dynamic values. This makes your queries more 
flexible and consistent.
```

---

### 7. Field Count Mismatch

**Your Code:**
```php
$query = [
    'sql' => "INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?)",
    'params' => [123, 456] // Missing quantity!
];
```

**Shomer's Suggestion:**
```
💡 SECURE QUERY SUGGESTION:

SQL:
INSERT INTO table (field1, field2, field3) VALUES (?, ?, ?)

PHP Code Example:
// Ensure the number of fields matches the number of placeholders
$fields = ['field1', 'field2', 'field3'];
$placeholders = array_fill(0, count($fields), '?');
$sql = "INSERT INTO table (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";

Explanation:
The number of fields in your INSERT statement must match the number of 
VALUES placeholders. Count them carefully or use array functions to ensure 
they match.
```

## 📧 Suggestions in Email Reports

When errors are sent via email, suggestions are included:

```
═══════════════════════════════════════════════════
❌ ERRORS DETECTED:
  • CRITICAL ERROR: DELETE without WHERE clause

💡 SECURE QUERY SUGGESTION:
──────────────────────────────────────────────────
SQL:
DELETE FROM table WHERE id = ?

PHP Code Example:
$stmt = $pdo->prepare("DELETE FROM table WHERE id = ?");
$stmt->execute([$id]);

Explanation:
CRITICAL: DELETE without WHERE clause will affect ALL rows...
──────────────────────────────────────────────────
```

## 🌐 Suggestions in HTML Reports

HTML reports display suggestions in an attractive, expandable format:

```html
<details open>
  <summary><strong>💡 Secure Query Suggestion</strong></summary>
  <div style="background: #e8f5e9; padding: 15px;">
    <p><strong>SQL:</strong></p>
    <pre><code>DELETE FROM table WHERE id = ?</code></pre>
    
    <p><strong>PHP Code Example:</strong></p>
    <pre><code>$stmt = $pdo->prepare(...);</code></pre>
    
    <p><strong>Explanation:</strong></p>
    <p>CRITICAL: DELETE without WHERE clause...</p>
  </div>
</details>
```

## ⚙️ Enabling Suggestions

Suggestions are **automatic in verbose mode**:

```php
// Suggestions enabled
$report = QueryValidator::validate($query, true, true); // verbose = true

// No suggestions (faster)
$report = QueryValidator::validate($query, true, false); // verbose = false
```

## 🎓 Educational Value

Shomer's suggestions help you:

1. **Learn by doing** - See correct code immediately
2. **Understand why** - Clear explanations for each suggestion
3. **Build habits** - Reinforce secure coding practices
4. **Save time** - No need to search Stack Overflow

## 💻 Accessing Suggestions Programmatically

```php
$report = QueryValidator::validate($query, true, true);

if (isset($report['suggestion'])) {
    // Display to developer
    echo "💡 TIP: " . $report['suggestion']['explanation'] . "\n";
    echo "Try this instead:\n";
    echo $report['suggestion']['code'] . "\n";
    
    // Log for learning
    error_log("Shomer suggestion: " . json_encode($report['suggestion']));
    
    // Send to IDE plugin
    sendToIDE($report['suggestion']);
}
```

## 🚫 When Suggestions Are NOT Generated

Suggestions are only generated when:
- ❌ Verbose mode is disabled
- ❌ The query is already perfect
- ❌ The issue is too complex to auto-fix

## 🎯 Best Practices

### ✅ DO:
- Enable verbose mode during development
- Read and apply suggestions
- Share suggestions with junior developers
- Use suggestions as learning material

### ❌ DON'T:
- Don't blindly copy-paste without understanding
- Don't enable verbose mode in production (performance)
- Don't ignore the explanations

## 📊 Example Workflow

```php
// 1. Write your query
$query = [
    'sql' => "DELETE FROM cache",
    'params' => []
];

// 2. Validate with verbose mode
$report = QueryValidator::validate($query, true, true);

// 3. Check for suggestions
if ($report['suggestion']) {
    // 4. Read the explanation
    echo $report['suggestion']['explanation'];
    
    // 5. Apply the fix
    $fixedQuery = [
        'sql' => "DELETE FROM cache WHERE expires_at < ?",
        'params' => [time()]
    ];
    
    // 6. Validate again - should pass!
    $newReport = QueryValidator::validate($fixedQuery, true, true);
}
```

## 🌟 Real-World Impact

**Before Shomer:**
```php
// Developer writes insecure code
$query = "DELETE FROM users"; // Oops!

// Production disaster
// All users deleted!
```

**With Shomer:**
```php
// Developer writes insecure code
$query = "DELETE FROM users";

// Shomer catches it in development
$report = QueryValidator::validate($query, true, true);

// Developer sees suggestion:
// "Add WHERE clause: DELETE FROM users WHERE id = ?"

// Developer fixes it
$query = [
    'sql' => "DELETE FROM users WHERE inactive = ? AND last_login < ?",
    'params' => [1, $cutoffDate]
];

// Production is safe!
```

## 🎉 Summary

Shomer's suggestions transform error messages from:

❌ **"You have an error"**

Into:

✅ **"Here's what's wrong, why it matters, and exactly how to fix it!"**

This makes Shomer not just a guardian of your database, but a **mentor for secure SQL development**!

---

**Shomer (שומר)** - Guarding your queries and teaching you along the way! 🛡️📚
