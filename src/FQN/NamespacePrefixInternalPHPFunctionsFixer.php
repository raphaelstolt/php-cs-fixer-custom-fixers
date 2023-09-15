<?php

declare(strict_types=1);

namespace Stolt\PhpCsFixer\FQN;

use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Preg;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use Stolt\PhpCsFixer\AbstractFixer;

final class NamespacePrefixInternalPHPFunctionsFixer extends AbstractFixer implements ConfigurableFixerInterface, WhitespacesAwareFixerInterface
{
    /**
     * @internal
     */
    public const C_ENABLE_PREFIX = 'enable_prefix';

    private string $description = 'Adds an namespace prefix to internal PHP functions to minimize opcode conversions.';

    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        if ($this->configuration[self::C_ENABLE_PREFIX] === false) {
            return;
        }

        $internalPHPFunctionNames = \get_defined_functions()['internal'];

        /** @var \PhpCsFixer\Tokenizer\Token $functionToken */
        foreach ($tokens as $index => $functionToken) {
            if (!$functionToken->isGivenKind([T_STRING])) {
                continue;
            }

            if (\in_array($functionToken->getContent(), $internalPHPFunctionNames)) {
                $previousIsNamespaceSeparator = false;
                $prevIndex = $tokens->getPrevMeaningfulToken($index);

                if ($tokens[$prevIndex]->isGivenKind(T_NS_SEPARATOR)) {
                    $previousIsNamespaceSeparator = true;
                }

                if (!$previousIsNamespaceSeparator) {
                    $this->addNamespacePrefix($tokens, $index);
                }
            }
        }
    }

    private function addNamespacePrefix(Tokens $tokens, int $index)
    {
        $slices = [
            $tokens->getNonWhitespaceSibling($index - 1, 1) => [new Token([T_NS_SEPARATOR, "\\"])],
        ];
        $tokens->insertSlices($slices);
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_STRING);
    }

    public function getPriority(): int
    {
        return -36;
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        $codeBefore = '<?php
function method(
    string $a
): void {
    is_array(["example"]);

    return strlen($a);
}' . PHP_EOL;

        $codeAfterwards = '<?php
function method(
    string $a
): void {
    is_array(["example"]);

    return \strlen($a);
}' . PHP_EOL;

        return new FixerDefinition(
            $this->description,
            [
                new CodeSample($codeBefore),
                new CodeSample($codeAfterwards, [self::C_ENABLE_PREFIX => true]),
            ],
        );
    }

    protected function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([
            (new FixerOptionBuilder(self::C_ENABLE_PREFIX, $this->description))
                ->setAllowedTypes(['bool'])
                ->setDefault(true)
                ->getOption(),
        ]);
    }
}