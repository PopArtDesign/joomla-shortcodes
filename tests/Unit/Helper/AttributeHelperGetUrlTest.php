<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

class AttributeHelperGetUrlTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_url_from_url_attribute(): void
    {
        $attributes = ['url' => 'https://example.com/doc.pdf'];
        $this->assertEquals('https://example.com/doc.pdf', AttributeHelper::getUrl($attributes, ''));
    }

    /**
     * @test
     */
    public function it_gets_url_from_content(): void
    {
        $this->assertEquals('https://example.com/doc.pdf', AttributeHelper::getUrl([], 'https://example.com/doc.pdf'));
    }

    /**
     * @test
     */
    public function it_gets_url_from_positional_attribute(): void
    {
        $attributes = ['https://example.com/doc.pdf'];
        $this->assertEquals('https://example.com/doc.pdf', AttributeHelper::getUrl($attributes, ''));
    }

    /**
     * @test
     */
    public function it_throws_exception_for_invalid_url_attribute(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL provided in "url" attribute: invalid-url');
        AttributeHelper::getUrl(['url' => 'invalid-url'], '');
    }

    /**
     * @test
     */
    public function it_throws_exception_for_invalid_url_in_content(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL provided in content: invalid-url');
        AttributeHelper::getUrl([], 'invalid-url');
    }

    /**
     * @test
     */
    public function it_throws_exception_for_invalid_url_as_positional_attribute(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL provided as positional attribute: invalid-url');
        AttributeHelper::getUrl(['invalid-url'], '');
    }

    /**
     * @test
     */
    public function it_throws_exception_when_no_url_found(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A valid embed URL was not found.');
        AttributeHelper::getUrl([], '');
    }

    /**
     * @test
     */
    public function it_returns_relative_url_when_relative_is_true(): void
    {
        $this->assertEquals('/media/doc.pdf', AttributeHelper::getUrl(['url' => '/media/doc.pdf'], '', true));
        $this->assertEquals('doc.pdf', AttributeHelper::getUrl([], 'doc.pdf', true));
        $this->assertEquals('../doc.pdf', AttributeHelper::getUrl(['../doc.pdf'], '', true));
    }

    /**
     * @test
     */
    public function it_throws_exception_for_relative_url_when_relative_is_false(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL provided in "url" attribute: /media/doc.pdf');
        AttributeHelper::getUrl(['url' => '/media/doc.pdf'], '', false);
    }
}
