<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\GoogleDocs;

\defined('_JEXEC') or die;

class GoogleDocsTest extends TestCase
{
    public function testGoogleDocsUrlInContentIsProcessed(): void
    {
        $shortcode = new GoogleDocs();
        $result = $shortcode([], 'https://docs.google.com/document/d/12345/edit');
        $this->assertStringContainsString('docs.google.com/document/d/12345/preview', $result);
    }

    public function testGoogleDocsUrlAsAttributeIsProcessed(): void
    {
        $shortcode = new GoogleDocs();
        $result = $shortcode(['url' => 'https://docs.google.com/document/d/12345/edit'], '');
        $this->assertStringContainsString('docs.google.com/document/d/12345/preview', $result);
    }

    public function testNonGoogleDocsUrlIsNotProcessed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not extract Google Docs/Drive file details from URL: https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $shortcode = new GoogleDocs();
        $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'], '');
    }
}
