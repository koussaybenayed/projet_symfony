<?php
namespace App\Service;

class CensorshipService
{
    private $bannedWords = ['fuck', 'shit', 'curse', 'insult', 'merde'];

    public function censor(string $text): string
    {
        foreach ($this->bannedWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            $replacement = str_repeat('*', strlen($word));
            $text = preg_replace($pattern, $replacement, $text);
        }
        return $text;
    }
}
