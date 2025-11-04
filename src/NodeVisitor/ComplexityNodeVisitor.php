<?php

declare(strict_types=1);

namespace Artemeon\CognitiveComplexity\NodeVisitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Artemeon\CognitiveComplexity\DataCollector\CognitiveComplexityDataCollector;
use Artemeon\CognitiveComplexity\NodeAnalyzer\ComplexityAffectingNodeFinder;

final class ComplexityNodeVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly CognitiveComplexityDataCollector $cognitiveComplexityDataCollector,
        private readonly ComplexityAffectingNodeFinder $complexityAffectingNodeFinder
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $this->complexityAffectingNodeFinder->isIncrementingNode($node)) {
            return null;
        }

        $this->cognitiveComplexityDataCollector->increaseOperation();

        return null;
    }
}
