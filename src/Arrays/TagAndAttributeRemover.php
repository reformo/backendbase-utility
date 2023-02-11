<?php

declare(strict_types=1);

namespace Backendbase\Utility\Arrays;

use DOMAttr;
use DOMDocument;
use DOMElement;
use DOMNamedNodeMap;
use DOMNodeList;

use function array_keys;
use function count;
use function explode;
use function html_entity_decode;
use function in_array;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function strip_tags;
use function strpos;
use function trim;

use const LIBXML_HTML_NODEFDTD;
use const LIBXML_HTML_NOIMPLIED;

class TagAndAttributeRemover
{
    private static array $urlAttributes = ['href', 'src'];

    private function __construct(private DOMDocument $domHtml, private array $currentTags, private array $allowedTags, private array $allowedUrlPrefixes)
    {
    }

    public static function cleanHtml(string $html, string $allowedTagsAndAttributesList, ?string $allowedUrlPrefixes = ''): string
    {
        $allowedTagsAndAttributes    = self::extractAllowedTags($allowedTagsAndAttributesList);
        $domHtml                     = new DOMDocument();
        $domHtml->substituteEntities = true;
        $domHtml->formatOutput       = true;
        $html                        = '<?xml encoding="utf-8" ?>' . $html;
        libxml_use_internal_errors(true);
        $domHtml->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $domHtml->encoding = 'utf-8';

        $domHtml->normalizeDocument();
        $remover = new self(
            $domHtml,
            self::extractCurrentTags($domHtml),
            $allowedTagsAndAttributes,
            self::safeExplodeString(';', $allowedUrlPrefixes, false)
        );
        $remover->removeAttributes();

        return $remover->removeTags();
    }

    private static function safeExplodeString(string $delimiter, string $string, ?bool $allowEmptyArrayElement = true): array
    {
        if (str_contains($string, $delimiter)) {
            return explode($delimiter, $string);
        }

        if ($string === '') {
            return [];
        }

        return $allowEmptyArrayElement ? [$string, ''] : [$string];
    }

    private static function extractAllowedTags(string $allowedTagsAndAttributes): array
    {
        $allowedTagList = explode(';', $allowedTagsAndAttributes);

        $allowedTags = [];
        foreach ($allowedTagList as $tagConfig) {
            $parts = self::safeExplodeString('|', $tagConfig);
            if (count($parts) <= 0) {
                continue;
            }

            [$tagName, $attributes] = $parts;
            $allowedTags[$tagName]  = self::safeExplodeString(',', $attributes);
        }

        return $allowedTags;
    }

    private static function extractCurrentTags(DOMDocument $domHtml): array
    {
        $currentTags = [];
        foreach ($domHtml->getElementsByTagName('*') as $node) {
            $currentTags[$node->tagName] = 1;
        }

        return array_keys($currentTags);
    }

    private function removeAttributes(): void
    {
        foreach ($this->currentTags as $tag) {
            $this->scanNodes($this->domHtml->getElementsByTagName($tag));
        }
    }

    private function scanNodes(DOMNodeList $nodes): void
    {
        foreach ($nodes as $node) {
            $this->scanNode($node);
        }
    }

    private function scanNode(DOMElement $node): void
    {
        foreach ($this->getAttributesNames($node->attributes) as $attribute) {
            $this->removeNotAllowedAttributes($node, $attribute, $this->allowedTags[$node->tagName]);
        }
    }

    private function getAttributesNames(DOMNamedNodeMap $attributes): array
    {
        $attributeNames = [];
        foreach ($attributes as $attribute) {
            $attributeNames[] = $attribute->nodeName;
        }

        return $attributeNames;
    }

    private function removeNotAllowedAttributes(DOMElement $node, string $attributeName, $allowedAttributes): bool
    {
        $attribute = $node->getAttributeNode($attributeName);
        if (! in_array($attribute->name, $allowedAttributes, true)) {
            return $node->removeAttribute($attributeName);
        }

        if (in_array($attributeName, self::$urlAttributes, true)) {
            return $this->removeAttributesWithNotAllowedUrlPrefixes($node, $attribute);
        }

        return false;
    }

    private function removeAttributesWithNotAllowedUrlPrefixes(DOMElement $node, DOMAttr $attribute): ?bool
    {
        $isAllowed = 0;
        foreach ($this->allowedUrlPrefixes as $allowedUrlPrefix) {
            if (str_starts_with($attribute->nodeValue, $allowedUrlPrefix)) {
                $isAllowed = 1;
                break;
            }
        }

        if ($isAllowed === 0) {
            return $node->removeAttribute($attribute->nodeName);
        }

        return false;
    }

    private function removeTags(): string
    {
        return trim(strip_tags(html_entity_decode($this->domHtml->saveHTML()), array_keys($this->allowedTags)));
    }
}
