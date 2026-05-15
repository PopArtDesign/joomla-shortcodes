<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Exception\UserException;
use JoomlaShortcoder\Plugin\Content\Shortcodes\AbstractShortcodeHandler;
use PHPUnit\Framework\TestCase;

\defined('_JEXEC') or die;

class AbstractShortcodeHandlerTest extends TestCase
{
    public function testInvokeCallsProcess(): void
    {
        $attributes = ['foo' => 'bar'];
        $content = 'content';

        $handler = new class () extends AbstractShortcodeHandler {
            public function process(array $attributes, string $content): string
            {
                return $attributes['foo'] . ': ' . $content;
            }
        };

        $result = $handler->__invoke($attributes, $content);

        $this->assertEquals('bar: content', $result);
    }

    public function testInvokeCatchesUserExceptionAndCallsShowError(): void
    {
        $attributes = ['foo' => 'bar'];
        $content = 'content';

        $handler = new class () extends AbstractShortcodeHandler {
            public function process(array $attributes, string $content): string
            {
                throw new UserException('User-friendly error message.');
            }
        };

        $result = $handler->__invoke($attributes, $content);

        $this->assertStringContainsString('<div class="shortcode-error"', $result);
        $this->assertStringContainsString('User-friendly error message', $result);
        $this->assertStringContainsString('AbstractShortcodeHandler@anonymous', $result);
    }

    public function testErrorThrowsUserException(): void
    {
        $this->expectException(UserException::class);
        $this->expectExceptionMessage('This is an internal error.');

        $handler = new class () extends AbstractShortcodeHandler {
            public function process(array $attributes, string $content): string
            {
                return '';
            }

            public function callError(string $message): void
            {
                $this->error($message);
            }
        };

        $handler->callError('This is an internal error.');
    }
}
