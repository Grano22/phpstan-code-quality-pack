<?php

declare(strict_types=1);

namespace Grano22\CodeQualityPack;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\Rules\RuleErrorBuilder;

final class ReasonRequiredInDeprecationRule implements Rule
{
    private Lexer $phpDocLexer;
    private PhpDocParser $phpDocParser;

    public function __construct(Lexer $lexer, TypeParser $typeParser, ConstExprParser $constExprParser)
    {
        $this->phpDocLexer = $lexer;
        $this->phpDocParser = new PhpDocParser(new ParserConfig([]), $typeParser, $constExprParser);
    }


    public function getNodeType(): string
    {
        return Node\Stmt::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (
            !($node instanceof ClassMethod ||
                $node instanceof Class_ ||
                $node instanceof Function_ ||
                $node instanceof Property ||
                $node instanceof ClassConst)
        ) {
            return [];
        }

        $errors = [];

        $docComment = $node->getDocComment();

        if ($docComment !== null) {
            $tokens = new TokenIterator($this->phpDocLexer->tokenize($docComment->getText()));
            $docNode = $this->phpDocParser->parse($tokens);

            foreach ($docNode->getDeprecatedTagValues() as $deprecatedTagValueNode) {
                if (!$deprecatedTagValueNode->description) {
                    $errorMessage = sprintf(
                        'Empty @deprecated annotation detected on line %d. Please provide a reason or remove the annotation.',
                        $node->getLine()
                    );
                    $errors[] = RuleErrorBuilder::message($errorMessage)
                        ->identifier('codeQualityPack.reasonRequiredInDeprecationRule')
                        ->line($node->getLine())
                        ->build()
                    ;
                }
            }
        }

        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                $attrName = $attribute->name->toString();

                if (strcasecmp($attrName, 'Deprecated') !== 0 && strcasecmp($attrName, '\\Deprecated') !== 0) {
                    continue;
                }

                if (!$this->isNodeEmpty($attribute)) {
                    continue;
                }

                $errorMessage = sprintf(
                    'Empty #[Deprecated] attribute detected on line %d. Please provide a reason or remove the attribute.',
                    $node->getLine()
                );
                $errors[] = RuleErrorBuilder::message($errorMessage)
                    ->identifier('codeQualityPack.reasonRequiredInDeprecationRule')
                    ->line($node->getLine())
                    ->build()
                ;
            }
        }

        return $errors;
    }

    private function isNodeEmpty(Node $attribute): bool
    {
        if(empty($attribute->args)) {
            return true;
        }

        $value = $attribute->args[0]->value;

        return $value instanceof Node\Scalar\String_ && trim($value->value) === '';
    }
}
