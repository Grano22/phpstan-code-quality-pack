Code Quality Check Pack
===

This package contains useful rules to support best practises in your codebase.

Actual roles in the package:

- Grano22\CodeQualityPack\ReasonRequiredInDeprecationRule - Checks if any deprecated annotation has no **reason**.

### Installation

You can install the package via composer:

```bash
composer require --dev grano22/code_quality_pack
```

### Usage

:open_file_folder: phpstan.dist.neon

```yaml
services:
    -
        class: Grano22\CodeQualityPack\ReasonRequiredInDeprecationRule
        arguments:
            - @PHPStan\PhpDocParser\Lexer\Lexer
            - @PHPStan\PhpDocParser\Parser\TypeParser
            - @PHPStan\PhpDocParser\Parser\ConstExprParser
        tags:
            - phpstan.rules.rule
```