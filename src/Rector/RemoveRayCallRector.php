<?php

namespace Spatie\Ray\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class RemoveRayCallRector extends AbstractRector implements RectorInterface
{
    public function getRuleDefinition(): \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new RuleDefinition('Remove function', [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
$x = 'something';
ray($x);
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
$x = 'something';
CODE_SAMPLE
            , ['ray'])]);
    }

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    public function refactor(Node $node): ?int
    {
        $expr = $node->expr;

        if (! $expr instanceof FuncCall) {
            return null;
        }

        if (! $this->isName($expr->name, 'ray')) {
            return null;
        }

        return NodeTraverser::REMOVE_NODE;
    }
}
