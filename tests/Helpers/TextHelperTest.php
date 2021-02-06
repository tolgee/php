<?php

namespace Tolgee\Core\Helpers;

use PHPUnit\Framework\TestCase;

class TextHelperTest extends TestCase
{

    public function testSplitOnNonEscapedDelimiter()
    {
        $string = "This is dot not to split \\. and this is dot . to split. \\ nice\\. This one \\\\. split. yezz\\.";
        $result = TextHelper::splitOnNonEscapedDelimiter($string, ".");
        $this->assertSame($result, [
            'This is dot not to split . and this is dot ',
            ' to split',
            '  nice. This one \\',
            ' split',
            ' yezz.'
        ]);
    }

    public function testIsCharEscaped()
    {
        $string = "\\c \\c \\\\c";
        $this->assertTrue(TextHelper::isCharEscaped(1, str_split($string)));
        $this->assertTrue(TextHelper::isCharEscaped(4, str_split($string)));
        $this->assertFalse(TextHelper::isCharEscaped(8, str_split($string)));
    }

    public function testRemoveEscapesSimple()
    {
        $removed = "this is text with escaped \\.";
        $this->assertEquals("this is text with escaped .", TextHelper::removeEscapes($removed));
    }

    public function testRemoveEscapesMixed()
    {
        $removed = "this \\\is \\text \\\\. with \\. escaped \\.";
        $this->assertEquals('this \is text \. with . escaped .', TextHelper::removeEscapes($removed));
    }
}
