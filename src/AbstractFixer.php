<?php

declare(strict_types=1);

namespace Stolt\PhpCsFixer;

abstract class AbstractFixer extends \PhpCsFixer\AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return sprintf('Stolt/%s', parent::getName());
    }
}
