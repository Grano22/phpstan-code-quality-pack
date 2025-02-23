<?php

declare(strict_types=1);

namespace Grano22\CodeQualityPack\Tests\Unit;

use Grano22\CodeQualityPack\ReasonRequiredInDeprecationRule;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

class ReasonRequiredInDeprecationRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        $config = new ParserConfig(usedAttributes: []);
        $lexer = new Lexer($config);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);

        return new ReasonRequiredInDeprecationRule($lexer, $typeParser, $constExprParser);
    }

    #[Test]
    public function testReasonRequiredInDeprecation(): void
    {
        // Arrange

        // Act & Assert
        $this->analyse([__DIR__ . '/data/ClassWithEmptyDeprecatedDocBlock.php'], [
            [
                'Empty @deprecated annotation detected on line 6. Please provide a reason or remove the annotation.',
                6,
            ],
            [
                'Empty @deprecated annotation detected on line 14. Please provide a reason or remove the annotation.',
                14
            ]
        ]);
    }

//    public static function getAdditionalConfigFiles(): array
//    {
//        return [__DIR__ . '/data/deprecated.dist.neon'];
//    }
}
