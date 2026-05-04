<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Helper;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;
use PHPUnit\Framework\TestCase;

class HtmlHelperTest extends TestCase
{
    /**
     * @dataProvider attributesProvider
     */
    public function testAttributes(array $attributes, array $booleanAttrs, string $expected): void
    {
        $this->assertSame($expected, HtmlHelper::attributes($attributes, $booleanAttrs));
    }

    public static function attributesProvider(): array
    {
        return [
            'empty attributes' => [[], [], ''],
            'simple attributes' => [['class' => 'foo', 'id' => 'bar'], [], 'class="foo" id="bar"'],
            'boolean true attribute' => [['disabled' => true], [], 'disabled'],
            'boolean false attribute' => [['disabled' => false], [], ''],
            'boolean string attribute "true"' => [['allowfullscreen' => 'true'], ['allowfullscreen'], 'allowfullscreen'],
            'boolean string attribute "yes"' => [['allowfullscreen' => 'yes'], ['allowfullscreen'], 'allowfullscreen'],
            'boolean string attribute "1"' => [['allowfullscreen' => '1'], ['allowfullscreen'], 'allowfullscreen'],
            'boolean string attribute "false"' => [['allowfullscreen' => 'false'], ['allowfullscreen'], ''],
            'attribute with quotes' => [['title' => 'it\'s a "quote"'], [], 'title="it&apos;s a &quot;quote&quot;"'],
            'non-scalar value' => [['foo' => []], [], ''],
            'integer key' => [[0 => 'foo'], [], ''],
            'empty string value' => [['class' => ''], [], ''],
            'mixed attributes' => [
                ['class' => 'foo', 'disabled' => true, 'data-value' => 'bar', 'allow' => false, 'readonly' => 'true'],
                ['readonly'],
                'class="foo" disabled data-value="bar" readonly'
            ],
        ];
    }

    /**
     * @dataProvider tagProvider
     */
    public function testTag(string $tag, array $attributes, string $content, bool $selfClosing, array $booleanAttrs, string $expected): void
    {
        $this->assertSame($expected, HtmlHelper::tag($tag, $attributes, $content, $selfClosing, $booleanAttrs));
    }

    public static function tagProvider(): array
    {
        return [
            'simple div' => ['div', [], '', false, [], '<div></div>'],
            'div with content' => ['div', [], 'Hello', false, [], '<div>Hello</div>'],
            'div with attributes' => ['div', ['class' => 'container'], '', false, [], '<div class="container"></div>'],
            'div with attributes and content' => ['div', ['class' => 'container'], 'Hello', false, [], '<div class="container">Hello</div>'],
            'self-closing img' => ['img', ['src' => 'image.jpg'], '', true, [], '<img src="image.jpg" />'],
            'self-closing input with boolean' => ['input', ['type' => 'text', 'required' => true], '', true, [], '<input type="text" required />'],
            'meta tag with charset' => ['meta', ['charset' => 'utf-8'], '', true, [], '<meta charset="utf-8" />'],
            'button with boolean disabled attribute' => ['button', ['type' => 'submit', 'disabled' => true], 'Submit', false, ['disabled'], '<button type="submit" disabled>Submit</button>'],
            'button with disabled false' => ['button', ['disabled' => false], 'Click', false, [], '<button>Click</button>'],
            'script tag with src' => ['script', ['src' => 'app.js'], '', false, [], '<script src="app.js"></script>'],
        ];
    }

    /**
    * @dataProvider divProvider
    */
    public function testDiv(array $attributes, string $content, string $expected): void
    {
        $this->assertSame($expected, HtmlHelper::div($attributes, $content));
    }

    public static function divProvider(): array
    {
        return [
            'simple div' => [[], '', '<div></div>'],
            'div with content' => [[], 'Hello World', '<div>Hello World</div>'],
            'div with class attribute' => [['class' => 'my-class'], '', '<div class="my-class"></div>'],
            'div with id and class attributes' => [['id' => 'my-id', 'class' => 'my-class'], 'Content', '<div id="my-id" class="my-class">Content</div>'],
        ];
    }

    /**
    * @dataProvider iframeProvider
    */
    public function testIframe(string $url, array $attributes, string $expected): void
    {
        $this->assertSame($expected, HtmlHelper::iframe($url, $attributes));
    }

    public static function iframeProvider(): array
    {
        return [
            'simple iframe' => ['https://example.com', [], '<iframe src="https://example.com"></iframe>'],
            'iframe with width and height' => ['https://example.com/video', ['width' => '560', 'height' => '315'], '<iframe src="https://example.com/video" width="560" height="315"></iframe>'],
            'iframe with allowfullscreen' => ['https://example.com/embed', ['allowfullscreen' => true], '<iframe src="https://example.com/embed" allowfullscreen></iframe>'],
            'iframe with title and referrerpolicy' => ['https://example.com/doc', ['title' => 'Document', 'referrerpolicy' => 'no-referrer-when-downgrade'], '<iframe src="https://example.com/doc" title="Document" referrerpolicy="no-referrer-when-downgrade"></iframe>'],
        ];
    }

    /**
    * @dataProvider objectProvider
    */
    public function testObject(string $url, string $type, array $attributes, string $content, string $expected): void
    {
        $this->assertSame($expected, HtmlHelper::object($url, $type, $attributes, $content));
    }

    public static function objectProvider(): array
    {
        return [
            'simple pdf object' => ['document.pdf', 'application/pdf', [], '', '<object data="document.pdf" type="application/pdf"></object>'],
            'object with width and height' => ['image.svg', 'image/svg+xml', ['width' => '100%', 'height' => 'auto'], '', '<object data="image.svg" type="image/svg+xml" width="100%" height="auto"></object>'],
            'object with fallback content' => ['document.pdf', 'application/pdf', [], '<p>Your browser does not support PDFs.</p>', '<object data="document.pdf" type="application/pdf"><p>Your browser does not support PDFs.</p></object>'],
        ];
    }

    /**
    * @dataProvider scriptProvider
    */
    public function testScript(string $src, array $attributes, string $expected): void
    {
        $this->assertSame($expected, HtmlHelper::script($src, $attributes));
    }

    public static function scriptProvider(): array
    {
        return [
            'simple script' => ['app.js', [], '<script src="app.js"></script>'],
            'script with async and defer' => ['defer.js', ['async' => true, 'defer' => true], '<script src="defer.js" async defer></script>'],
            'script with integrity' => ['cdn.js', ['integrity' => 'sha256-abc', 'crossorigin' => 'anonymous'], '<script src="cdn.js" integrity="sha256-abc" crossorigin="anonymous"></script>'],
        ];
    }
}
