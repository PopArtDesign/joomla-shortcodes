<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Embed;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\GoogleDocs;

class GoogleDocsTest extends TestCase
{
    private GoogleDocs $googleDocs;

    protected function setUp(): void
    {
        $this->googleDocs = new GoogleDocs();
    }

    public function testSupportsGoogleDocsUrl(): void
    {
        $this->assertTrue($this->googleDocs->supports('https://docs.google.com/document/d/1BxiM9uQ5kZ7v8W6Y2X3T4R5P6O7I8U9J0K/edit'));
    }

    public function testSupportsGoogleSheetsUrl(): void
    {
        $this->assertTrue($this->googleDocs->supports('https://docs.google.com/spreadsheets/d/1CjK9L0mN2oP3qR4sT5uV6wX7yZ8aBcDeFgH/edit'));
    }

    public function testSupportsGoogleSlidesUrl(): void
    {
        $this->assertTrue($this->googleDocs->supports('https://docs.google.com/presentation/d/1IjK9L0mN2oP3qR4sT5uV6wX7yZ8aBcDeFgH/edit'));
    }

    public function testSupportsGoogleDriveUrl(): void
    {
        $this->assertTrue($this->googleDocs->supports('https://drive.google.com/file/d/1BxiM9uQ5kZ7v8W6Y2X3T4R5P6O7I8U9J0K/view'));
    }

    public function testDoesNotSupportOtherUrls(): void
    {
        $this->assertFalse($this->googleDocs->supports('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertFalse($this->googleDocs->supports('https://example.com'));
    }

    public function testBasicGoogleDocsEmbedding(): void
    {
        $url = 'https://docs.google.com/document/d/1BxiM9uQ5kZ7v8W6Y2X3T4R5P6O7I8U9J0K/edit';
        $result = $this->googleDocs->process($url, ['_' => []]);
        $expectedEmbedUrl = 'https://docs.google.com/document/d/1BxiM9uQ5kZ7v8W6Y2X3T4R5P6O7I8U9J0K/preview';

        $this->assertStringContainsString($expectedEmbedUrl, $result);
        $this->assertStringContainsString('width="100%"', $result);
        $this->assertStringNotContainsString('height="', $result);
        $this->assertStringContainsString('title="Google document"', $result);
    }

    public function testBasicGoogleSheetsEmbedding(): void
    {
        $url = 'https://docs.google.com/spreadsheets/d/1CjK9L0mN2oP3qR4sT5uV6wX7yZ8aBcDeFgH/edit';
        $result = $this->googleDocs->process($url, ['_' => []]);
        $expectedEmbedUrl = 'https://docs.google.com/spreadsheets/d/1CjK9L0mN2oP3qR4sT5uV6wX7yZ8aBcDeFgH/preview';

        $this->assertStringContainsString($expectedEmbedUrl, $result);
        $this->assertStringContainsString('width="100%"', $result);
        $this->assertStringNotContainsString('height="', $result);
        $this->assertStringContainsString('title="Google document"', $result);
    }

    public function testBasicGoogleSlidesEmbedding(): void
    {
        $url = 'https://docs.google.com/presentation/d/1IjK9L0mN2oP3qR4sT5uV6wX7yZ8aBcDeFgH/edit';
        $result = $this->googleDocs->process($url, ['_' => []]);
        $expectedEmbedUrl = 'https://docs.google.com/presentation/d/1IjK9L0mN2oP3qR4sT5uV6wX7yZ8aBcDeFgH/embed';

        $this->assertStringContainsString($expectedEmbedUrl, $result);
        $this->assertStringContainsString('width="100%"', $result);
        $this->assertStringNotContainsString('height="', $result);
        $this->assertStringContainsString('title="Google document"', $result);
    }

    public function testBasicGoogleDriveFileEmbedding(): void
    {
        $url = 'https://drive.google.com/file/d/1BxiM9uQ5kZ7v8W6Y2X3T4R5P6O7I8U9J0K/view';
        $result = $this->googleDocs->process($url, ['_' => []]);
        $expectedEmbedUrl = 'https://drive.google.com/file/d/1BxiM9uQ5kZ7v8W6Y2X3T4R5P6O7I8U9J0K/preview';

        $this->assertStringContainsString($expectedEmbedUrl, $result);
        $this->assertStringContainsString('width="100%"', $result);
        $this->assertStringNotContainsString('height="', $result);
        $this->assertStringContainsString('title="Google document"', $result);
    }

    public function testCustomDimensions(): void
    {
        $url = 'https://docs.google.com/document/d/1BxiM9uQ5kZ7v8W6Y2X3T4R5P6O7I8U9J0K/edit';
        $result = $this->googleDocs->process($url, ['width' => '800px', 'height' => '500px', '_' => []]);

        $this->assertStringContainsString('width="800px"', $result);
        $this->assertStringContainsString('height="500px"', $result);
    }

    public function testInvalidUrlReturnsEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not extract Google Docs/Drive file details from URL: not-a-valid-url');
        $this->googleDocs->process('not-a-valid-url', ['_' => []]);
    }

    public function testUnsupportedUrlReturnsEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not extract Google Docs/Drive file details from URL: https://www.google.com');
        $this->googleDocs->process('https://www.google.com', ['_' => []]);
    }
}
