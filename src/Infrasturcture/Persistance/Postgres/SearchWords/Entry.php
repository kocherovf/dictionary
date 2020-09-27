<?php

declare(strict_types=1);


namespace Dictionary\Infrasturcture\Persistance\Postgres\SearchWords;

final class Entry
{
    private string $wordId;
    private string $meaningId;
    private string $text;
    private string $translation;

    public function __construct(string $wordId, string $meaningId, string $text, string $translation)
    {
        $this->wordId = $wordId;
        $this->meaningId = $meaningId;
        $this->text = $text;
        $this->translation = $translation;
    }

    public function toArray(): array
    {
        return [
            'wordId' => $this->wordId,
            'wordText' => $this->text,
            'meaningId' => $this->meaningId,
            'translation' => $this->translation
        ];
    }
}
