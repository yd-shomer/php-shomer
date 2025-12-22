# Contributing to PHP Shomer (שומר)

Thank you for your interest in contributing to Shomer! We welcome contributions from the community.

## 🤝 How to Contribute

### Reporting Bugs

If you find a bug, please create an issue with:
- Clear description of the problem
- Steps to reproduce
- Expected vs actual behavior
- PHP version and environment details
- SQL query example (if applicable)

### Suggesting Features

We love new ideas! When suggesting a feature:
- Explain the use case
- Describe the expected behavior
- Consider if it fits Shomer's philosophy of being a development tool

### Pull Requests

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/my-new-feature`
3. **Write tests** for your changes
4. **Ensure tests pass**: `composer test`
5. **Follow PSR-12 coding standards**
6. **Commit with clear messages**: `git commit -m 'Add feature: X'`
7. **Push to your fork**: `git push origin feature/my-new-feature`
8. **Create a Pull Request**

## 🎯 Development Guidelines

### Code Style

- Follow PSR-12 coding standards
- Use meaningful variable and method names
- Add PHPDoc comments to all public methods
- Keep methods focused and concise

### Testing

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage
```

### Commit Messages

Use clear, descriptive commit messages:

```
✅ Good:
- "Add validation for TRUNCATE statements"
- "Fix placeholder counting for named parameters"
- "Improve error message clarity in SecurityValidator"

❌ Bad:
- "fix bug"
- "update code"
- "changes"
```

## 🛡️ Shomer Philosophy

When contributing, keep in mind Shomer's core principles:

1. **Development Tool**: Shomer is for development, not production
2. **Zero Production Impact**: When disabled, it should have zero overhead
3. **Educational**: Error messages should teach best practices
4. **Security-Focused**: Help developers write secure SQL
5. **Simple to Use**: One-line integration, easy configuration

## 📋 Areas We Need Help

- [ ] Support for PostgreSQL specific syntax
- [ ] Support for SQLite specific syntax
- [ ] Better detection of complex injection patterns
- [ ] Performance optimizations
- [ ] More comprehensive test coverage
- [ ] Translations (error messages in multiple languages)
- [ ] Documentation improvements
- [ ] Example integrations (Laravel, Symfony, etc.)

## 🌍 Translation

We'd love to have error messages in multiple languages! If you're fluent in a language other than English and French, consider contributing translations.

## 📝 Documentation

Help improve the documentation:
- Fix typos and grammatical errors
- Add more examples
- Improve clarity
- Translate to other languages

## ✅ Code Review Process

All submissions require review. We use GitHub pull requests for this purpose. We'll review your code for:

- Functionality
- Code quality
- Test coverage
- Documentation
- Adherence to Shomer's philosophy

## 🎓 First Time Contributors

New to open source? No problem! Look for issues tagged with `good-first-issue` or `help-wanted`.

## 📞 Questions?

- Open a [Discussion](https://github.com/votre-username/php-shomer/discussions)
- Ask in the PR/Issue comments
- Check existing documentation

## 🙏 Thank You

Every contribution, no matter how small, helps make Shomer better for everyone. Thank you for being part of the guardian community! 🛡️

---

**Shomer (שומר)** - Because your database deserves a guardian.
