<?php

declare(strict_types=1);

namespace Dictionary\Domain\Model\Word;

use Dictionary\Domain\Model\Meaning\Definition;
use Dictionary\Domain\Model\Meaning\Example;
use Dictionary\Domain\Model\Meaning\Meaning;
use Dictionary\Domain\Model\Meaning\MeaningId;
use Dictionary\Domain\Model\Meaning\Translation;

final class Word
{
    private WordId $id;
    private Text $text;

    public function __construct(WordId $id, Text $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    public function getId(): WordId
    {
        return $this->id;
    }

    public function getText(): Text
    {
        return $this->text;
    }

    public function setText(Text $text): void
    {
        $this->text = $text;
    }

    public function makeMeaning(
        MeaningId $meaningId,
        Translation $translation,
        Definition $definition,
        Example ...$examples
    ): Meaning {
        return new Meaning($this->id, $meaningId, $translation, $definition, $examples);
    }
}
