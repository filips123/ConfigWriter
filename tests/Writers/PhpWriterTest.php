<?php

namespace ConfigWriter\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ConfigWriter\Config;
use ConfigWriter\Writers\PhpWriter;

class PhpWriterTest extends TestCase
{
    /**
     * @var \ConfigWriter\Config
     */
    protected $config;

    public function setUp()
    {
        $this->config = new Config;
    }

    /**
     * @covers \ConfigWriter\Writers\PhpWriter::write()
     * @covers \ConfigWriter\Writers\PhpWriter::export()
     * @covers \ConfigWriter\Writers\PhpWriter::comment()
     */
    public function testWriteConfig()
    {
        $database = $this->config->addSection('database', [], 'Database settings');
        $database->addRecord('user', 'root', 'Database username');
        $database->addRecord('pass', '', 'Database password');

        $development = $this->config->addSection('development', [], 'Development settings');
        $development->addRecord('debug', true, 'Debugging');
        $development->addRecord('errors', 32767, 'Error reporting');

        $filename = sys_get_temp_dir() . '/ConfigWriterTests/' . uniqid(rand()) . '.php';
        $this->config->toFile($filename);

        $expected = <<<EOD
<?php

return [
    // Database settings
    'database' => [
        'user' => 'root', // Database username
        'pass' => '', // Database password
    ],
    // Development settings
    'development' => [
        'debug' => true, // Debugging
        'errors' => 32767, // Error reporting
    ],
];

EOD;
        $actual = file_get_contents($filename);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \ConfigWriter\Writers\PhpWriter::comment()
     */
    public function testCStyleComment()
    {
        $reflection = new ReflectionClass(PhpWriter::class);
        $method = $reflection->getMethod('comment');
        $method->setAccessible(true);

        $expected = '// Hello World';
        $actual = $method->invokeArgs(new PhpWriter, ['Hello World', 'c-style']);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \ConfigWriter\Writers\PhpWriter::comment()
     */
    public function testPerlStyleComment()
    {
        $reflection = new ReflectionClass(PhpWriter::class);
        $method = $reflection->getMethod('comment');
        $method->setAccessible(true);

        $expected = '# Hello World';
        $actual = $method->invokeArgs(new PhpWriter, ['Hello World', 'perl-style']);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \ConfigWriter\Writers\PhpWriter::comment()
     */
    public function testMultiLineComment()
    {
        $reflection = new ReflectionClass(PhpWriter::class);
        $method = $reflection->getMethod('comment');
        $method->setAccessible(true);

        $expected = '/* Hello World */';
        $actual = $method->invokeArgs(new PhpWriter, ['Hello World', 'multi-line']);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \ConfigWriter\Writers\PhpWriter::comment()
     */
    public function testDocBlockComment()
    {
        $reflection = new ReflectionClass(PhpWriter::class);
        $method = $reflection->getMethod('comment');
        $method->setAccessible(true);

        $expected = '/** Hello World */';
        $actual = $method->invokeArgs(new PhpWriter, ['Hello World', 'doc-block']);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \ConfigWriter\Writers\PhpWriter::comment()
     */
    public function testUnknownComment()
    {
        $reflection = new ReflectionClass(PhpWriter::class);
        $method = $reflection->getMethod('comment');
        $method->setAccessible(true);

        $expected = '// Hello World';
        $actual = $method->invokeArgs(new PhpWriter, ['Hello World', 'unknown-comment']);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \ConfigWriter\Writers\PhpWriter::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['php'];
        $actual = PhpWriter::getSupportedExtensions();

        $this->assertEquals($expected, $actual);
    }
}
