<?php

namespace Spatie\Ray\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use Rector\Contract\Rector\RectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class RemoveRayCallRector extends AbstractRector implements RectorInterface
{
    public function getRuleDefinition(): \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new RuleDefinition('Remove Ray calls', [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
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

        if (! $expr instanceof FuncCall && ! $expr instanceof MethodCall) {
            return null;
        }

        if ($this->isName($expr->name, 'ray')) {
            return NodeTraverser::REMOVE_NODE;
        }

        if ($expr->var->name->parts && in_array('ray', $expr->var->name->parts)) {
            return NodeTraverser::REMOVE_NODE;
        }

        return null;
    }
}
