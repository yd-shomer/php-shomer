<?php
/**
 * PHP Shomer (שומר) - Execution Context Example
 * 
 * This example demonstrates how Shomer captures execution context
 * (file, line, URL, function) to make debugging easier.
 */

require __DIR__ . '/../vendor/autoload.php';

use Shomer\QueryValidator;
use Shomer\Reports\ValidationReport;

// Enable Shomer
define('SHOMER_ENABLED', true);

echo "╔════════════════════════════════════════════════════╗\n";
echo "║  🛡️  SHOMER - EXECUTION CONTEXT DEMO           ║\n";
echo "╚════════════════════════════════════════════════════╝\n\n";

// ============================================================================
// Example 1: Function Call Context
// ============================================================================

function validateUserLogin($username, $password) {
    echo "Example 1: Validating query from function validateUserLogin()\n";
    echo str_repeat("─", 50) . "\n";
    
    // This query has an error (missing parameter)
    $query = [
        'sql' => "SELECT * FROM users WHERE username = ? AND password = ?",
        'params' => [$username] // ❌ Missing password parameter!
    ];
    
    $report = QueryValidator::validate($query, true, false);
    
    displayContextInfo($report);
    
    return $report;
}

validateUserLogin('admin', 'secret123');

echo "\n";

// ============================================================================
// Example 2: Class Method Context
// ============================================================================

class UserRepository {
    public function findById($id) {
        echo "Example 2: Validating query from UserRepository::findById()\n";
        echo str_repeat("─", 50) . "\n";
        
        // Another error - DELETE without WHERE
        $query = [
            'sql' => "DELETE FROM users", // ❌ No WHERE clause!
            'params' => []
        ];
        
        $report = QueryValidator::validate($query, true, false);
        
        displayContextInfo($report);
        
        return $report;
    }
}

$repo = new UserRepository();
$repo->findById(123);

echo "\n";

// ============================================================================
// Example 3: Multiple Nested Calls
// ============================================================================

class OrderService {
    public function processOrder($orderId) {
        return $this->validateOrder($orderId);
    }
    
    private function validateOrder($orderId) {
        echo "Example 3: Validating query from nested method calls\n";
        echo str_repeat("─", 50) . "\n";
        
        $query = [
            'sql' => "UPDATE orders SET status = :status WHERE id = :id",
            'params' => [
                'status' => 'completed',
                'id' => $orderId
            ]
        ];
        
        $report = QueryValidator::validate($query, true, false);
        
        displayContextInfo($report);
        
        return $report;
    }
}

$orderService = new OrderService();
$orderService->processOrder(456);

echo "\n";

// ============================================================================
// Example 4: Email Report with Context
// ============================================================================

echo "Example 4: Email Report Format (with execution context)\n";
echo str_repeat("─", 50) . "\n";

// Simulate an email function
function customEmailHandler($body, $report) {
    echo "\n📧 EMAIL CONTENT:\n";
    echo str_repeat("=", 70) . "\n";
    echo $body;
    echo str_repeat("=", 70) . "\n";
}

// Query with error
$query = [
    'sql' => "INSERT INTO logs (message, level) VALUES (?, ?, ?)", // Too many placeholders
    'params' => ['Error occurred', 'ERROR'] // Only 2 params for 3 placeholders
];

$report = QueryValidator::validate($query, true, true, 'customEmailHandler');

echo "\n";

// ============================================================================
// Example 5: HTML Report with Context
// ============================================================================

echo "Example 5: HTML Report Format\n";
echo str_repeat("─", 50) . "\n\n";

$query = [
    'sql' => "SELECT * FROM products WHERE category = ?",
    'params' => ['electronics']
];

$report = QueryValidator::validate($query, true, false);
$html = ValidationReport::toHtml($report);

echo $html . "\n\n";

// ============================================================================
// Example 6: CLI Context (when running from command line)
// ============================================================================

echo "Example 6: CLI Context Detection\n";
echo str_repeat("─", 50) . "\n";

// In CLI, there's no URL, so Shomer shows script path instead
$query = [
    'sql' => "SELECT COUNT(*) FROM sessions WHERE expires_at > ?",
    'params' => [time()]
];

$report = QueryValidator::validate($query, true, false);

if (isset($report['context'])) {
    $ctx = $report['context'];
    echo "Context type: " . ($ctx['url'] ? "WEB" : "CLI") . "\n";
    echo "Script: " . ($ctx['script_name'] ?? 'N/A') . "\n";
}

echo "\n";

// ============================================================================
// Helper Function
// ============================================================================

function displayContextInfo(array $report): void
{
    if (!isset($report['context'])) {
        echo "⚠️  No context information available\n";
        return;
    }
    
    $ctx = $report['context'];
    
    echo "\n📍 EXECUTION CONTEXT:\n";
    echo "   File: " . ($ctx['file_relative'] ?? $ctx['file']) . "\n";
    echo "   Line: " . $ctx['line'] . "\n";
    
    if ($ctx['class']) {
        echo "   Method: " . $ctx['class'] . $ctx['type'] . $ctx['function'] . "()\n";
    } else {
        echo "   Function: " . $ctx['function'] . "()\n";
    }
    
    if ($ctx['url']) {
        echo "   URL: " . $ctx['url'] . "\n";
        echo "   HTTP Method: " . $ctx['method'] . "\n";
    } else {
        echo "   CLI Script: " . ($ctx['script_name'] ?? 'N/A') . "\n";
    }
    
    echo "\n";
    
    if ($report['nb_erreurs'] > 0) {
        echo "❌ ERRORS:\n";
        foreach ($report['erreurs'] as $error) {
            echo "   • $error\n";
        }
    }
    
    if ($report['nb_avertissements'] > 0) {
        echo "⚠️  WARNINGS:\n";
        foreach ($report['avertissements'] as $warning) {
            echo "   • $warning\n";
        }
    }
    
    echo "\n";
}

echo "╔════════════════════════════════════════════════════╗\n";
echo "║  With execution context, you get 'ready-to-use'  ║\n";
echo "║  debugging info - no need to search where the     ║\n";
echo "║  error occurred!                                  ║\n";
echo "║                                                    ║\n";
echo "║  שומר - Your SQL Query Guardian                   ║\n";
echo "╚════════════════════════════════════════════════════╝\n";
