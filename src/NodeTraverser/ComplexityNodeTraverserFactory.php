<?php

declare(strict_types=1);

namespace Artemeon\CognitiveComplexity\NodeTraverser;

use PhpParser\NodeTraverser;
use Artemeon\CognitiveComplexity\NodeVisitor\ComplexityNodeVisitor;
use Artemeon\CognitiveComplexity\NodeVisitor\NestingNodeVisitor;

final readonly class ComplexityNodeTraverserFactory
{
    public function __construct(
        private NestingNodeVisitor $nestingNodeVisitor,
        private ComplexityNodeVisitor $complexityNodeVisitor
    ) {
    }

    public function create(): NodeTraverser
    {
        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor($this->nestingNodeVisitor);
        $nodeTraverser->addVisitor($this->complexityNodeVisitor);

        return $nodeTraverser;
    }
}
