<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Bonfim\Component\View\Inheritance;

class InheritanceTests extends TestCase
{
    public function testBasicInheritance(): void
    {
        $config = ['template_dir' => 'tests/Bonfim'];

        $expected = "Main layout\nChild block";
        $content  = '{extends \'test\'}';
        $content .= '{block \'content\'}';
        $content .= 'Child block';
        $content .= '{/block}';

        $test = fopen('tests/Bonfim/test.html', 'w+');
        fwrite($test, "Main layout\n{block 'content'}{/block}");
        fclose($test);

        $this->assertEquals($expected, (string) new Inheritance($content, $config));

        unlink('tests/Bonfim/test.html');
    }
}