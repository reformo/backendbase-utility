<?php

declare(strict_types=1);

namespace Selami\Stdlib;

use Selami\Stdlib\Exception\InvalidArgumentException;
use Transliterator;

class Slugifier
{
    private Transliterator $transliterator;
    /**
     * @param string|iterable $subject
     */
    private function __construct(private $subject)
    {
        $this->transliterator = Transliterator::create('Any-Latin; Latin-ASCII');
    }
    /**
     * @param mixed<string|iterable> $subject
     * @return string|iterable<string>
     */
    public static function slugify(mixed $subject): string|iterable
    {
        return (new self($subject))
            ->getSlugifiedResult();
    }
    /**
     * @return string|iterable<string>
     */
    private function getSlugifiedResult(): string|iterable
    {
        if (is_iterable($this->subject)) {
            return $this->getSlugifiedIterable($this->subject);
        }
        return $this->getSlugifiedString($this->subject);
    }

    private function getSlugifiedString($subject) : string
    {

        if (!is_string($subject)) {
            throw new InvalidArgumentException(
                sprintf('Only string or array of strings accepted but %s given', gettype($subject))
            );
        }
        $stringWithRemovedDiacritics = $this->transliterator->transliterate($subject);
        $stringWithRemovedWhite = preg_replace(['/\-/','/[\W]+/'], [' ','-'], $stringWithRemovedDiacritics);
        return strtolower($stringWithRemovedWhite);
    }

    /**
     * @return iterable<string>
     */
    private function getSlugifiedIterable(iterable $subject) : iterable
    {
        foreach ($subject as $item) {
            yield $this->getSlugifiedString($item);
        }
    }
}