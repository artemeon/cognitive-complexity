<?php

declare(strict_types=1);

namespace Artemeon\CognitiveComplexity;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Artemeon\CognitiveComplexity\DataCollector\CognitiveComplexityDataCollector;
use Artemeon\CognitiveComplexity\NodeTraverser\ComplexityNodeTraverserFactory;
use Artemeon\CognitiveComplexity\NodeVisitor\NestingNodeVisitor;

/**
 * @see \Artemeon\CognitiveComplexity\Tests\AstCognitiveComplexityAnalyzer\AstCognitiveComplexityAnalyzerTest
 *
 * implements the concept described in https://www.sonarsource.com/resources/white-papers/cognitive-complexity/
 */
final readonly class AstCognitiveComplexityAnalyzer
{
    public function __construct(
        private ComplexityNodeTraverserFactory $complexityNodeTraverserFactory,
        private CognitiveComplexityDataCollector $cognitiveComplexityDataCollector,
        private NestingNodeVisitor $nestingNodeVisitor
    ) {
    }

    public function analyzeClassLike(Class_ $class): int
    {
        $totalCognitiveComplexity = 0;
        foreach ($class->getMethods() as $classMethod) {
            $totalCognitiveComplexity += $this->analyzeFunctionLike($classMethod);
        }

        return $totalCognitiveComplexity;
    }

    /**
     * @api
     */
    public function analyzeFunctionLike(Function_ | ClassMethod $functionLike): int
    {
        $this->cognitiveComplexityDataCollector->reset();
        $this->nestingNodeVisitor->reset();

        $nodeTraverser = $this->complexityNodeTraverserFactory->create();
        $nodeTraverser->traverse([$functionLike]);

        return $this->cognitiveComplexityDataCollector->getCognitiveComplexity();
    }
}
