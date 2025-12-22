<?php
/**
 * PHP Shomer (שומר) - Secure Query Suggestions Example
 * 
 * This example demonstrates how Shomer provides helpful suggestions
 * for writing secure SQL queries when in verbose mode.
 */

require __DIR__ . '/../vendor/autoload.php';

use Shomer\QueryValidator;
use Shomer\Reports\ValidationReport;

// Enable Shomer
define('SHOMER_ENABLED', true);

echo "╔════════════════════════════════════════════════════╗\n";
echo "║  🛡️  SHOMER - SECURE QUERY SUGGESTIONS         ║\n";
echo "╚════════════════════════════════════════════════════╝\n\n";

// ============================================================================
// Example 1: Non-Prepared Query → Suggestion to use Prepared Statement
// ============================================================================

echo "Example 1: Non-Prepared Query (Insecure)\n";
echo str_repeat("─", 50) . "\n";

$unsafeQuery = "SELECT * FROM users WHERE email = 'user@example.com'";
$report1 = QueryValidator::validate($unsafeQuery, true, true); // verbose = true

displayReport($report1);

// ============================================================================
// Example 2: Parameter Count Mismatch → Suggestion to Fix
// ============================================================================

echo "\nExample 2: Parameter Count Mismatch\n";
echo str_repeat("─", 50) . "\n";

$query2 = [
    'sql' => "INSERT INTO users (name, email, age) VALUES (?, ?, ?)",
    'params' => ['John Doe', 'john@example.com'] // Missing age parameter!
];

$report2 = QueryValidator::validate($query2, true, true);

displayReport($report2);

// ============================================================================
// Example 3: DELETE Without WHERE → Suggestion to Add WHERE
// ============================================================================

echo "\nExample 3: DELETE Without WHERE Clause\n";
echo str_repeat("─", 50) . "\n";

$query3 = [
    'sql' => "DELETE FROM sessions",
    'params' => []
];

$report3 = QueryValidator::validate($query3, true, true);

displayReport($report3);

// ============================================================================
// Example 4: UPDATE Without WHERE → Suggestion to Add WHERE
// ============================================================================

echo "\nExample 4: UPDATE Without WHERE Clause\n";
echo str_repeat("─", 50) . "\n";

$query4 = [
    'sql' => "UPDATE users SET status = ?",
    'params' => ['inactive']
];

$report4 = QueryValidator::validate($query4, true, true);

displayReport($report4);

// ============================================================================
// Example 5: SELECT * → Suggestion for Specific Columns
// ============================================================================

echo "\nExample 5: SELECT * (Not Recommended)\n";
echo str_repeat("─", 50) . "\n";

$query5 = [
    'sql' => "SELECT * FROM products WHERE category = ?",
    'params' => ['electronics']
];

$report5 = QueryValidator::validate($query5, true, true);

displayReport($report5);

// ============================================================================
// Example 6: Hardcoded Values in Prepared Statement
// ============================================================================

echo "\nExample 6: Hardcoded Values in Prepared Statement\n";
echo str_repeat("─", 50) . "\n";

$query6 = [
    'sql' => "INSERT INTO logs (message, level) VALUES (?, 'ERROR')", // 'ERROR' is hardcoded
    'params' => ['Database connection failed']
];

$report6 = QueryValidator::validate($query6, true, true);

displayReport($report6);

// ============================================================================
// Example 7: Field Count Mismatch in INSERT
// ============================================================================

echo "\nExample 7: Field Count Mismatch\n";
echo str_repeat("─", 50) . "\n";

$query7 = [
    'sql' => "INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?)",
    'params' => [123, 456] // Missing quantity!
];

$report7 = QueryValidator::validate($query7, true, true);

displayReport($report7);

// ============================================================================
// Example 8: HTML Report with Suggestion
// ============================================================================

echo "\nExample 8: HTML Report Format\n";
echo str_repeat("─", 50) . "\n\n";

$query8 = [
    'sql' => "DELETE FROM cache",
    'params' => []
];

$report8 = QueryValidator::validate($query8, true, true);
$html = ValidationReport::toHtml($report8);

// Save HTML to file for viewing in browser
file_put_contents('/tmp/shomer_suggestion_example.html', $html);
echo "HTML report saved to: /tmp/shomer_suggestion_example.html\n";
echo "Open it in a browser to see the formatted suggestion!\n\n";

// ============================================================================
// Helper Function
// ============================================================================

function displayReport(array $report): void
{
    $statusIcon = $report['status'] === 'success' ? '✅' : '❌';
    
    echo "\n$statusIcon Status: " . strtoupper($report['status']) . "\n";
    echo "Errors: {$report['nb_erreurs']} | Warnings: {$report['nb_avertissements']}\n\n";
    
    if (!empty($report['erreurs'])) {
        echo "❌ ERRORS:\n";
        foreach ($report['erreurs'] as $error) {
            echo "  • $error\n";
        }
        echo "\n";
    }
    
    if (!empty($report['avertissements'])) {
        echo "⚠️  WARNINGS:\n";
        foreach ($report['avertissements'] as $warning) {
            echo "  • $warning\n";
        }
        echo "\n";
    }
    
    // Display the suggestion!
    if (!empty($report['suggestion'])) {
        echo "💡 SECURE QUERY SUGGESTION:\n";
        echo str_repeat("═", 70) . "\n";
        
        if (isset($report['suggestion']['query'])) {
            echo "\nSecure SQL:\n";
            echo "  " . $report['suggestion']['query'] . "\n";
        }
        
        if (isset($report['suggestion']['code'])) {
            echo "\nPHP Code Example:\n";
            $codeLines = explode("\n", $report['suggestion']['code']);
            foreach ($codeLines as $line) {
                echo "  " . $line . "\n";
            }
        }
        
        if (isset($report['suggestion']['explanation'])) {
            echo "\nExplanation:\n";
            $explanationLines = explode("\n", wordwrap($report['suggestion']['explanation'], 65));
            foreach ($explanationLines as $line) {
                echo "  " . $line . "\n";
            }
        }
        
        echo str_repeat("═", 70) . "\n";
    }
    
    echo "\n";
}

echo "╔════════════════════════════════════════════════════╗\n";
echo "║  Shomer not only finds problems - it shows you   ║\n";
echo "║  how to fix them! Learn secure coding practices  ║\n";
echo "║  as you develop.                                  ║\n";
echo "║                                                    ║\n";
echo "║  שומר - Your SQL Query Guardian & Teacher        ║\n";
echo "╚════════════════════════════════════════════════════╝\n";
