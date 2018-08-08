<?php

namespace ConfigWriter\Tests;

use PHPUnit\Framework\TestCase;
use ConfigWriter\Config;

class ConfigTest extends TestCase
{
    /**
     * @var \ConfigWriter\Config
     */
    protected $config;

    public function setUp()
    {
        $config = new Config;

        $config->addRecord('settings.development.debug', true, 'Debugging');
        $config->addRecord('settings.development.errors', E_ALL, 'Error reporting');

        $config->addRecord('database.host', 'localhost', 'Database hostname');
        $config->addRecord('database.port', 3306, 'Database port');

        $config->addRecord('title', 'My Website', 'Website title');
        $config->addRecord('description', 'This is my cool website!', 'Website descroption');

        $database = $config->addSection('database', [], 'Database settings');

        $database->addRecord('database.user', 'root', 'Database username');
        $database->addRecord('database.pass', '', 'Database password');

        $this->config = $config;
    }

    /**
     * @covers \ConfigWriter\Config::getWriter()
     * @covers \ConfigWriter\Config::toFile()
     * @covers \ConfigWriter\Config::toString()
     */
    public function testWriting()
    {
        $filename = sys_get_temp_dir() . '/ConfigWriterTests/' . uniqid(rand()) . '.php';
        $this->config->toFile($filename);

        $this->assertFileExists($filename);
    }

    /**
     * @covers \ConfigWriter\Config::getWriter()
     * @covers \ConfigWriter\Config::toFile()
     * @covers \ConfigWriter\Config::toString()
     */
    public function testWritingWithDistExtension()
    {
        $filename = sys_get_temp_dir() . '/ConfigWriterTests/' . uniqid(rand()) . '.php.dist';
        $this->config->toFile($filename);

        $this->assertFileExists($filename);
    }

    /**
     * @covers \ConfigWriter\Config::toFile()
     * @covers \ConfigWriter\Config::toString()
     */
    public function testWritingCustomWriter()
    {
        $filename = sys_get_temp_dir() . '/ConfigWriterTests/' . uniqid(rand()) . '.txt';
        $this->config->toFile($filename, new \ConfigWriter\Writers\PhpWriter);

        $this->assertFileExists($filename);
    }

    /**
     * @covers \ConfigWriter\Config::getWriter()
     * @covers \ConfigWriter\Config::toFile()
     *
     * @expectedException \ConfigWriter\Exceptions\UnsupportedFormatException
     */
    public function testWritingUnsupportedFormat()
    {
        $filename = sys_get_temp_dir() . '/ConfigWriterTests/' . uniqid(rand()) . '.png';
        $this->config->toFile($filename);
    }
}
