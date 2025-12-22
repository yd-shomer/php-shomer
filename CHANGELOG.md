# Changelog

All notable changes to PHP Shomer will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-01-15

### 🎉 Initial Release

#### Added
- ✅ Core query validation engine
- ✅ Support for prepared statements (PDO & MySQLi style)
- ✅ Syntax validation (parentheses, quotes, structure)
- ✅ Security validation (SQL injection patterns, dangerous keywords)
- ✅ Parameter count validation for prepared statements
- ✅ Detection of mixed placeholder types (? and :named)
- ✅ Email notification system for critical errors
- ✅ **Automatic execution context capture** (file, line, URL, function/method)
- ✅ **Secure query suggestions in verbose mode** (shows how to fix issues)
- ✅ Verbose mode for detailed analysis
- ✅ Zero-overhead production bypass mode
- ✅ Support for INSERT, UPDATE, SELECT, DELETE queries
- ✅ Detection of missing WHERE clauses in UPDATE/DELETE
- ✅ Warning for SELECT * usage
- ✅ Detection of hardcoded values in prepared queries
- ✅ HTML report generation
- ✅ Quick validation with `isValid()` method
- ✅ Comprehensive documentation and examples
- ✅ MIT License

#### Query Types Supported
- INSERT validation (field/value count matching)
- UPDATE validation (WHERE clause checking)
- SELECT validation (JOIN detection, SELECT * warnings)
- DELETE validation (WHERE clause requirement)

#### Security Checks
- SQL injection pattern detection
- Dangerous keyword detection (DROP, TRUNCATE, EXEC, etc.)
- SQL comment detection (--, /*, #)
- Superglobal variable detection ($_GET, $_POST, etc.)
- Unescaped PHP variable detection

#### Developer Experience
- Simple one-line integration
- PSR-4 autoloading
- Composer support
- Clear, educational error messages
- English and French messages
- Emoji-enhanced output for better readability
- **Execution context auto-capture**: Every error report includes file path, line number, function/method name, URL (web) or script path (CLI) - debugging info comes "ready to use"!
- **Secure query suggestions**: In verbose mode, Shomer provides corrected SQL, PHP code examples, and explanations for each issue - learn as you develop!

### 🛡️ Philosophy
Shomer (שומר) means "Guardian" in Hebrew. This tool acts as a vigilant guardian over your SQL queries during development, catching errors and security issues before they reach production.

---

## [Unreleased]

### Planned Features
- [ ] PostgreSQL specific syntax support
- [ ] SQLite specific syntax support
- [ ] Transaction validation
- [ ] Stored procedure validation
- [ ] Integration examples for popular frameworks (Laravel, Symfony, CodeIgniter)
- [ ] CLI tool for batch validation
- [ ] Git pre-commit hook integration
- [ ] VS Code extension
- [ ] Multilingual support (Spanish, German, Hebrew, Arabic)
- [ ] Performance benchmarking tools
- [ ] Custom validation rule system

---

[1.0.0]: https://github.com/votre-username/php-shomer/releases/tag/v1.0.0
[Unreleased]: https://github.com/votre-username/php-shomer/compare/v1.0.0...HEAD
