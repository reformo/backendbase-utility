<?php

declare(strict_types=1);

namespace Selami\Stdlib;

use Selami\Stdlib\Exception\InvalidArgumentException;
use Transliterator;

class Slugifier
{
    /**
     * @var string|array $subject
     */
    private $subject;

    private Transliterator $transliterator;
    /**
     * @param string|iterable $subject
     */
    private function __construct($subject)
    {
        $this->subject = $subject;
        $this->transliterator = Transliterator::create('Any-Latin; Latin-ASCII');
    }
    /**
     * @param mixed<string|iterable> $subject
     * @return string|iterable<string>
     */
    public static function slugify($subject)
    {
        return (new self($subject))
            ->getSlugifiedResult();
    }
    /**
     * @return string|iterable<string>
     */
    private function getSlugifiedResult()
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
     * @param iterable $subject
     * @return iterable<string>
     */
    private function getSlugifiedIterable(iterable $subject) : iterable
    {
        foreach ($subject as $item) {
            yield $this->getSlugifiedString($item);
        }
    }
}