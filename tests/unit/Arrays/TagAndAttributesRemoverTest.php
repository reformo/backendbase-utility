<?php

declare(strict_types=1);

namespace UnitTest\Arrays;

use PHPUnit\Framework\TestCase;
use Selami\Stdlib\Arrays\TagAndAttributeRemover;

use function trim;

final class TagAndAttributesRemoverTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRemoveTagsAndAttributesSuccessfully(): void
    {
        $html                     = <<<HTML
            <div><a href="https://reformo.net" target="_blank"><img src="http://url.to.file.which/not.exist" onerror=alert(document.cookie);></a></div>
HTML;
        $allowedTagsAndAttributes = 'a|href,target;img|src,style;div';
        $cleanedHtml              = TagAndAttributeRemover::cleanHtml(trim($html), $allowedTagsAndAttributes, 'http;https');
        $this->assertSame('<div><a href="https://reformo.net" target="_blank"><img src="http://url.to.file.which/not.exist"></a></div>', $cleanedHtml);
    }

    /**
     * @test
     */
    public function shouldRemoveTagsSuccessfully(): void
    {
        $html                     = <<<HTML
            <div><a href="https://reformo.net" target="_blank"><img src="http://url.to.file.which/not.exist" onerror=alert(document.cookie);></a></div>
HTML;
        $allowedTagsAndAttributes = 'a;img;div';
        $cleanedHtml              = TagAndAttributeRemover::cleanHtml(trim($html), $allowedTagsAndAttributes, 'http;https');
        $this->assertSame('<div><a><img></a></div>', $cleanedHtml);
    }

    /**
     * @test
     */
    public function shouldRemoveAttributesWithHarmfulValuesSuccessfully(): void
    {
        $html                     = <<<HTML
            <div class="div-class"><a href="https://reformo.net" target="_blank"><img src="javascript:alert('XSS');" onerror=alert(document.cookie); style="color:red"></a></div>
HTML;
        $allowedTagsAndAttributes = 'a|href,target;img|src,style;div';
        $cleanedHtml              = TagAndAttributeRemover::cleanHtml(trim($html), $allowedTagsAndAttributes, 'https');
        $this->assertSame('<div><a href="https://reformo.net" target="_blank"><img style="color:red"></a></div>', $cleanedHtml);
    }
}
