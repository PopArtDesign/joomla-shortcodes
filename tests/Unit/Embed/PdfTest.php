<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Tests\Unit\Embed;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Pdf;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testSupportsPdfUrls(string $url, bool $supported)
    {
        $handler = new Pdf();

        $this->assertSame($supported, $handler->supports($url));
    }

    public function testProcessesPdfUrl()
    {
        $url = 'https://example.com/document.pdf';
        $handler = new Pdf();
        $attributes = ['width' => '800', 'height' => '600'];
        $result = $handler->process($url, $attributes);

        $this->assertStringContainsString('<object', $result);
        $this->assertStringContainsString('data="https://example.com/document.pdf"', $result);
        $this->assertStringContainsString('type="application/pdf"', $result);
        $this->assertStringContainsString('width="800"', $result);
        $this->assertStringContainsString('height="600"', $result);
        $this->assertStringContainsString('embed-pdf', $result);
        $this->assertStringContainsString('download the PDF file', $result);
    }

    public static function urlProvider(): array
    {
        return [
            'pdf file' => ['https://example.com/document.pdf', true],
            'pdf file with query' => ['https://example.com/document.pdf?v=1', true],
            'uppercase extension' => ['https://example.com/DOCUMENT.PDF', true],
            'not a pdf' => ['https://example.com/document.docx', false],
            'google doc' => ['https://docs.google.com/document/d/FILE_ID/edit', false],
        ];
    }
}
