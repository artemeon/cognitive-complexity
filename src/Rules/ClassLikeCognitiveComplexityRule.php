<?php

declare(strict_types=1);

namespace Artemeon\CognitiveComplexity\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Artemeon\CognitiveComplexity\AstCognitiveComplexityAnalyzer;
use Artemeon\CognitiveComplexity\Configuration;
use Artemeon\CognitiveComplexity\Enum\RuleIdentifier;

/**
 * @see \Artemeon\CognitiveComplexity\Tests\Rules\ClassLikeCognitiveComplexityRule\ClassLikeCognitiveComplexityRuleTest
 */
final readonly class ClassLikeCognitiveComplexityRule implements Rule
{
    public const string ERROR_MESSAGE = 'Keep class cognitive complexity under %d';

    public function __construct(
        private AstCognitiveComplexityAnalyzer $astCognitiveComplexityAnalyzer,
        private Configuration $configuration
    ) {
    }

    /**
     * @return class-string<Node>
     */
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classLike = $node->getOriginalNode();
        if (! $classLike instanceof Class_) {
            return [];
        }

        $measuredCognitiveComplexity = $this->astCognitiveComplexityAnalyzer->analyzeClassLike($classLike);
        if ($measuredCognitiveComplexity <= $this->configuration->getMaxClassCognitiveComplexity()) {
            return [];
        }

        $message = sprintf(
            self::ERROR_MESSAGE,
            $this->configuration->getMaxClassCognitiveComplexity()
        );

        return [RuleErrorBuilder::message($message)->identifier(RuleIdentifier::CLASS_LIKE_COMPLEXITY)->build()];
    }
}
