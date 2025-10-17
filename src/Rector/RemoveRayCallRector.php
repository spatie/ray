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
ray()->label('debug');
ray($data)->red()->small();
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

        if ($this->isRayCall($expr)) {
            return NodeTraverser::REMOVE_NODE;
        }

        return null;
    }

    private function isRayCall(Node $node): bool
    {
        if ($node instanceof FuncCall && $this->isName($node->name, 'ray')) {
            return true;
        }

        if ($node instanceof MethodCall) {
            return $this->isRayCall($node->var);
        }

        return false;
    }
}
