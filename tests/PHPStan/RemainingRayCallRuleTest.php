<?php

namespace Spatie\Ray\Tests\PHPStan;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Ray\PHPStan\RemainingRayCallRule;

class RemainingRayCallRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new RemainingRayCallRule();
    }

    #[DataProvider('failingTestCasesProvider')]
    public function testTheRuleCanDetectRayCalls(string $path, int $line): void
    {
        $this->analyse([$path], [
            [
                'Remaining ray call in application code',
                $line,
            ],
        ]);
    }

    #[DataProvider('passingTestCasesProvider')]
    public function testTheRuleWillNotRaiseWhenNoRayCallIsPerformed(string $path): void
    {
        $this->analyse([$path], []);
    }

    public static function failingTestCasesProvider()
    {
        yield [__DIR__ . '/testdata/ClassContainingARayCall.php', 9];
        yield [__DIR__ . '/testdata/PHPFileContainingARayCall.php', 3];
    }

    public static function passingTestCasesProvider()
    {
        yield [__DIR__ . '/testdata/ClassNotContainingARayCall.php'];
        yield [__DIR__ . '/testdata/PHPFileNotContainingARayCall.php'];
    }
}
