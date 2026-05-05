<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Pdf;

\defined('_JEXEC') or die;

class PdfTest extends TestCase
{
    public function testPdfUrlInContentIsProcessed(): void
    {
        $shortcode = new Pdf();
        $result = $shortcode([], 'https://example.com/document.pdf');
        $this->assertStringContainsString('example.com/document.pdf', $result);
        $this->assertStringContainsString('object', $result);
        $this->assertStringContainsString('type="application/pdf"', $result);
    }

    public function testPdfUrlAsAttributeIsProcessed(): void
    {
        $shortcode = new Pdf();
        $result = $shortcode(['url' => 'https://example.com/document.pdf'], '');
        $this->assertStringContainsString('example.com/document.pdf', $result);
        $this->assertStringContainsString('object', $result);
        $this->assertStringContainsString('type="application/pdf"', $result);
    }

    public function testNonPdfUrlIsNotProcessed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The provided URL is not a PDF file.');
        $shortcode = new Pdf();
        $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'], '');
    }

    public function testFallbackMessageIsInsideObjectTag(): void
    {
        $shortcode = new Pdf();
        $result = $shortcode([], 'https://example.com/document.pdf');
        $this->assertMatchesRegularExpression('/<object.*>.*<p>.*PDF viewer.*<\/p>.*<\/object>/s', $result);
    }

    public function testPdfWithRelativeUrlIsProcessed(): void
    {
        $shortcode = new Pdf();
        $result = $shortcode([], '/media/document.pdf');
        $this->assertStringContainsString('/media/document.pdf', $result);
        $this->assertStringContainsString('object', $result);
        $this->assertStringContainsString('type="application/pdf"', $result);
    }
}
