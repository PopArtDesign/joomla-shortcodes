<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Tests\Unit\Embed;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Pdf;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    /**
     * @var Pdf
     */
    private $handler;

    protected function setUp(): void
    {
        $this->handler = new Pdf();
    }

    public function testSupports(): void
    {
        $this->assertTrue($this->handler->supports('http://example.com/doc.pdf'));
        $this->assertTrue($this->handler->supports('http://example.com/doc.PDF'));
        $this->assertTrue($this->handler->supports('/path/to/doc.pdf'));
        $this->assertFalse($this->handler->supports('http://example.com/doc.docx'));
        $this->assertFalse($this->handler->supports('http://example.com/'));
    }

    public function testProcess(): void
    {
        $url = 'http://example.com/doc.pdf';
        $attributes = [];

        $expected = '<object data="http://example.com/doc.pdf" type="application/pdf" width="100%" height="500px" title="PDF document"><p>It appears you don\'t have a PDF viewer for this browser. You can <a href="http://example.com/doc.pdf">click here to download the PDF file.</a></p></object>';
        $this->assertEquals($expected, $this->handler->process($url, $attributes));
    }

    public function testProcessWithAttributes(): void
    {
        $url = 'http://example.com/doc.pdf';
        $attributes = [
            'class' => 'my-class', // This class is now for the wrapper, not the object
            'width' => '800px',
            'height' => '600px',
            'title' => 'My PDF',
        ];

        $expected = '<object data="http://example.com/doc.pdf" type="application/pdf" width="800px" height="600px" title="My PDF"><p>It appears you don\'t have a PDF viewer for this browser. You can <a href="http://example.com/doc.pdf">click here to download the PDF file.</a></p></object>';
        $this->assertEquals($expected, $this->handler->process($url, $attributes));
    }
}
