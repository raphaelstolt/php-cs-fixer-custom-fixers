<?php

declare(strict_types=1);

namespace Stolt\PhpCsFixer\Tests\FQN;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Tests\Test\AbstractFixerTestCase;
use Stolt\PhpCsFixer\FQN\NamespacePrefixInternalPHPFunctionsFixer;

/**
 * @covers Stolt\PhpCsFixer\FQN\NamespacePrefixInternalPHPFunctionsFixer
 */
final class NamespacePrefixInternalPHPFunctionsFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFix(string $expected, ?string $input = null, array $configuration = null): void
    {
        if ($configuration !== null) {
            $this->fixer->configure($configuration);
        }

        $this->doTest($expected, $input);
    }

    public function provideFixCases(): iterable
    {
        yield [
            '<?php
use Foo\Bar;
use function \Stolt\Example\time;

class Good
{
    private function glob() {}

    public function firstMethod()
    {
        $array = ["some-value"];
        \is_array($array);
        \in_array("some-value", $array);
        \explode("-", "some-text");
        Example::glob("*");
        $this->glob();
        time();

        return \strlen($array[0]);
    }
}',
            '<?php
use Foo\Bar;
use function \Stolt\Example\time;

class Good
{
    private function glob() {}

    public function firstMethod()
    {
        $array = ["some-value"];
        is_array($array);
        in_array("some-value", $array);
        \explode("-", "some-text");
        Example::glob("*");
        $this->glob();
        time();

        return strlen($array[0]);
    }
}', ];
    }

    protected function createFixer(): AbstractFixer
    {
        return new NamespacePrefixInternalPHPFunctionsFixer();
    }
}
