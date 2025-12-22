<?php
/**
 * PHP Shomer (שומר) - Basic Usage Examples
 * 
 * This file demonstrates how to use Shomer to validate SQL queries
 */

require __DIR__ . '/../vendor/autoload.php';

use Shomer\QueryValidator;

// Enable Shomer in development
define('SHOMER_ENABLED', true);

echo "╔════════════════════════════════════════════════════╗\n";
echo "║  🛡️  PHP SHOMER (שומר) - USAGE EXAMPLES        ║\n";
echo "╚════════════════════════════════════════════════════╝\n\n";

// ============================================================================
// Example 1: Valid Prepared Statement
// ============================================================================
echo "Example 1: ✅ Valid Prepared Statement\n";
echo str_repeat("─", 50) . "\n";

$query1 = [
    'sql' => "INSERT INTO users (name, email, age) VALUES (?, ?, ?)",
    'params' => ['John Doe', 'john@example.com', 30]
];

$report1 = QueryValidator::validate($query1, true, true);
displayReport($report1);

// ============================================================================
// Example 2: Named Placeholders (PDO Style)
// ============================================================================
echo "\nExample 2: ✅ Named Placeholders\n";
echo str_repeat("─", 50) . "\n";

$query2 = [
    'sql' => "SELECT * FROM users WHERE email = :email AND status = :status",
    'params' => [
        'email' => 'user@example.com',
        'status' => 'active'
    ]
];

$report2 = QueryValidator::validate($query2, true, true);
displayReport($report2);

// ============================================================================
// Example 3: ❌ Parameter Count Mismatch
// ============================================================================
echo "\nExample 3: ❌ Parameter Count Mismatch\n";
echo str_repeat("─", 50) . "\n";

$query3 = [
    'sql' => "INSERT INTO users (name, email, age) VALUES (?, ?, ?)",
    'params' => ['John Doe', 'john@example.com'] // Missing one parameter!
];

$report3 = QueryValidator::validate($query3, true, false);
displayReport($report3);

// ============================================================================
// Example 4: ⚠️ Potential SQL Injection
// ============================================================================
echo "\nExample 4: ⚠️ Detecting Injection Attempts\n";
echo str_repeat("─", 50) . "\n";

$query4 = [
    'sql' => "SELECT * FROM users WHERE username = ?",
    'params' => ["admin' OR '1'='1"] // Injection attempt (will be detected)
];

$report4 = QueryValidator::validate($query4, true, true);
displayReport($report4);

// ============================================================================
// Example 5: ❌ Non-Prepared Query (Not Recommended)
// ============================================================================
echo "\nExample 5: ❌ Non-Prepared Query\n";
echo str_repeat("─", 50) . "\n";

$unsafeQuery = "SELECT * FROM users WHERE id = 123";
$report5 = QueryValidator::validate($unsafeQuery, true, false);
displayReport($report5);

// ============================================================================
// Example 6: ❌ DELETE Without WHERE
// ============================================================================
echo "\nExample 6: ❌ DELETE Without WHERE Clause\n";
echo str_repeat("─", 50) . "\n";

$query6 = [
    'sql' => "DELETE FROM users", // No WHERE clause - dangerous!
    'params' => []
];

$report6 = QueryValidator::validate($query6, true, false);
displayReport($report6);

// ============================================================================
// Example 7: ⚠️ Mixed Placeholders (Error)
// ============================================================================
echo "\nExample 7: ❌ Mixed Placeholder Types\n";
echo str_repeat("─", 50) . "\n";

$query7 = [
    'sql' => "SELECT * FROM users WHERE id = ? AND email = :email", // Mixed!
    'params' => [123, 'test@example.com']
];

$report7 = QueryValidator::validate($query7, true, false);
displayReport($report7);

// ============================================================================
// Example 8: ✅ Production Mode (Bypassed)
// ============================================================================
echo "\nExample 8: ⏭️  Production Mode (Bypassed)\n";
echo str_repeat("─", 50) . "\n";

$query8 = [
    'sql' => "SELECT * FROM users WHERE id = ?",
    'params' => [1]
];

// Disable Shomer - instant bypass
$report8 = QueryValidator::validate($query8, false);
displayReport($report8);

// ============================================================================
// Example 9: Quick Validation
// ============================================================================
echo "\nExample 9: ✅ Quick Validation (isValid method)\n";
echo str_repeat("─", 50) . "\n";

$query9 = [
    'sql' => "SELECT * FROM users WHERE id = ?",
    'params' => [1]
];

$isValid = QueryValidator::isValid($query9);
echo "Query is " . ($isValid ? "✅ VALID" : "❌ INVALID") . "\n";

// ============================================================================
// Helper Function to Display Reports
// ============================================================================
function displayReport(array $report): void
{
    $statusIcon = match($report['status']) {
        'success' => '✅',
        'error' => '❌',
        'bypassed' => '⏭️',
        default => '❓'
    };
    
    echo "\n$statusIcon Status: " . strtoupper($report['status']) . "\n";
    
    if (isset($report['nb_erreurs'])) {
        echo "Errors: {$report['nb_erreurs']} | Warnings: {$report['nb_avertissements']}\n";
    }
    
    if (!empty($report['erreurs'])) {
        echo "\n❌ ERRORS:\n";
        foreach ($report['erreurs'] as $error) {
            echo "  • $error\n";
        }
    }
    
    if (!empty($report['avertissements'])) {
        echo "\n⚠️  WARNINGS:\n";
        foreach ($report['avertissements'] as $warning) {
            echo "  • $warning\n";
        }
    }
    
    if (!empty($report['infos'])) {
        echo "\nℹ️  INFO (first 3):\n";
        $infos = array_slice($report['infos'], 0, 3);
        foreach ($infos as $info) {
            echo "  • $info\n";
        }
        if (count($report['infos']) > 3) {
            echo "  ... and " . (count($report['infos']) - 3) . " more\n";
        }
    }
    
    echo "\n";
}

echo "\n╔════════════════════════════════════════════════════╗\n";
echo "║  Shomer: Protecting your queries, one validation  ║\n";
echo "║  at a time. (שומר - Your SQL Query Guardian)      ║\n";
echo "╚════════════════════════════════════════════════════╝\n";
