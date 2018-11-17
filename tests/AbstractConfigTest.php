<?php

namespace ConfigWriter\Tests;

use PHPUnit\Framework\TestCase;
use ConfigWriter\AbstractConfig;
use ConfigWriter\Record;

class AbstractConfigTest extends TestCase
{
    /**
     * @var \ConfigWriter\AbstractConfig
     */
    protected $config;

    public function setUp()
    {
        $config = $this
            ->getMockBuilder('ConfigWriter\AbstractConfig')
            ->getMockForAbstractClass();

        $config->addRecord('settings.development.debug', true, 'Debugging');
        $config->addRecord('settings.development.errors', E_ALL, 'Error reporting');

        $config->addRecord('database.host', 'localhost', 'Database hostname');
        $config->addRecord('database.port', 3306, 'Database port');

        $config->addRecord(
            'website',
            [
                'title' => 'My Website',
                'description' => 'This is my cool website!',
            ],
            'Website details'
        );

        $this->config = $config;
    }

    /**
     * @covers \ConfigWriter\AbstractConfig::__construct()
     * @covers \ConfigWriter\AbstractConfig::stringToRecord()
     *
     * @param array $original Predefined data
     * @param array $expected Expected data
     *
     * @dataProvider providerConstructWithPredefinedData
     */
    public function testConstructWithPredefinedData($original, $expected)
    {
        $config = $this
            ->getMockBuilder('ConfigWriter\AbstractConfig')
            ->setConstructorArgs([$original])
            ->getMockForAbstractClass();

        $result = $config->data;

        $this->assertEquals($expected, $result);
    }

    /**
     * @covers \ConfigWriter\AbstractConfig::stringToRecord()
     */
    public function testStringToRecord()
    {
        $config = $this
            ->getMockBuilder('ConfigWriter\AbstractConfig')
            ->getMockForAbstractClass();

        $config->addSection(
            'database',
            [
                'database.host' => 'localhost',
                'database.port' => 3306,
            ],
            'Database settings'
        );

        $config->addRecord(
            'website',
            [
                'title' => new Record('My Website'),
                'description' => new Record('This is my cool website!'),
            ],
            'Website details'
        );

        $expected = [
            'database' => $this
            ->getMockBuilder('ConfigWriter\AbstractConfig')
            ->setConstructorArgs(
                [
                    [
                        'database' => new Record(
                            [
                                'host' => new Record('localhost'),
                                'port' => new Record(3306),
                            ]
                        ),
                    ],
                    'Database settings',
                ]
            )
            ->getMockForAbstractClass(),
            'website'  => new Record(
                [
                    'title' => new Record('My Website'),
                    'description' => new Record('This is my cool website!'),
                ],
                'Website details'
            ),
        ];

        $result = AbstractConfig::stringToRecord($config->data);

        $this->assertEquals($expected, $result);
    }


    /**
     * @covers \ConfigWriter\AbstractConfig::recordToString()
     */
    public function testRecordToString()
    {
        $config = $this
            ->getMockBuilder('ConfigWriter\AbstractConfig')
            ->getMockForAbstractClass();

        $config->addSection(
            'database',
            [
                'database.host' => 'localhost',
                'database.port' => 3306,
            ],
            'Database settings'
        );

        $config->addRecord(
            'website',
            [
                'title' => new Record('My Website'),
                'description' => new Record('This is my cool website!'),
            ],
            'Website details'
        );

        $expected = [
            'database' => [
                'host' => 'localhost',
                'port' => 3306,
            ],
            'website'  => [
                'title' => 'My Website',
                'description' => 'This is my cool website!',
            ],
        ];

        $result = AbstractConfig::recordToString($config->data);

        $this->assertEquals($expected, $result);

        $original = [
            'development' => [
                'debug' => new Record(true, 'Debugging'),
                'reporting' => new Record(E_ALL, 'Error reporting'),
            ],
        ];

        $expected = [
            'development' => [
                'debug' => true,
                'reporting' => E_ALL,
            ],
        ];

        $result = AbstractConfig::recordToString($original);

        $this->assertEquals($expected, $result);
    }

    /**
     * @covers \ConfigWriter\AbstractConfig::addRecord()
     * @covers \ConfigWriter\AbstractConfig::set()
     * @covers \ConfigWriter\AbstractConfig::stringToRecord()
     *
     * @param string     $key      Record's key
     * @param mixed|null $value    Record's value
     * @param mixed|null $comment  Record's comment
     * @param array      $expected Expected data
     *
     * @dataProvider providerAddRecord
     */
    public function testAddRecord($key, $value, $comment, $expected)
    {
        $config = $this
            ->getMockBuilder('ConfigWriter\AbstractConfig')
            ->getMockForAbstractClass();

        $config->addRecord($key, $value, $comment);

        $result = $config->data;

        $this->assertEquals($expected, $result);
    }

    /**
     * @covers \ConfigWriter\AbstractConfig::addSection()
     * @covers \ConfigWriter\AbstractConfig::set()
     * @covers \ConfigWriter\AbstractConfig::stringToRecord()
     */
    public function testAddSection()
    {
        $config = $this
            ->getMockBuilder('ConfigWriter\AbstractConfig')
            ->getMockForAbstractClass();

        $database = $config->addSection('database', [], 'Database settings');

        $database->addRecord('database.user', 'root', 'Database username');
        $database->addRecord('database.pass', '', 'Database password');

        $this->assertEquals('Database settings', $config['database']->comment);

        $this->assertEquals('root', $config['database']->data['database']['user']->value);
        $this->assertEquals('', $config['database']->data['database']['pass']->value);
    }

    /**
     * @covers \ConfigWriter\AbstractConfig::offsetGet()
     * @covers \ConfigWriter\Record::offsetGet()
     */
    public function testOffsetGet()
    {
        $this->assertEquals(true, $this->config['settings']['development']['debug']->value);
        $this->assertEquals('localhost', $this->config['database']['host']->value);
        $this->assertEquals('My Website', $this->config['website']['title']->value);
    }

    /**
     * @covers \ConfigWriter\AbstractConfig::offsetExists()
     * @covers \ConfigWriter\Record::offsetExists()
     */
    public function testOffsetExists()
    {
        $this->assertNotEmpty($this->config['database']['host']);
        $this->assertEmpty($this->config['user']['password']);
    }

    /**
     * @covers \ConfigWriter\AbstractConfig::offsetSet()
     * @covers \ConfigWriter\Record::offsetSet()
     * @covers \ConfigWriter\AbstractConfig::addRecord()
     * @covers \ConfigWriter\AbstractConfig::set()
     * @covers \ConfigWriter\AbstractConfig::stringToRecord()
     */
    public function testoffsetSet()
    {
        $username = 'test123';
        $this->config['username'] = $username;
        $this->assertEquals($username, $this->config['username']->value);

        $password = new Record('1234', 'Your password');
        $this->config['password'] = $password;
        $this->assertEquals($password, $this->config['password']);

        $keywords = new Record(['cool', 'website'], 'Website keywords');
        $this->config['website']['keywords'] = $keywords;
        $this->assertEquals($keywords, $this->config['website']['keywords']);
    }

    /**
     * @covers \ConfigWriter\AbstractConfig::offsetUnset()
     * @covers \ConfigWriter\Record::offsetUnset()
     * @covers \ConfigWriter\AbstractConfig::set()
     * @covers \ConfigWriter\AbstractConfig::stringToRecord()
     */
    public function testOffsetUnset()
    {
        unset($this->config['website']);
        $this->assertNull($this->config['website']);

        unset($this->config['settings']['development']['debug']);
        $this->assertNull($this->config['settings']['development']['debug']);
    }

    public function providerConstructWithPredefinedData()
    {
        return [
            [
                [
                    'username' => 'user123',
                    'password' => '1234',
                ],
                [
                    'username' => new Record('user123'),
                    'password' => new Record('1234'),
                ],
            ],

            [
                [
                    'username' => new Record('user123', 'Database username'),
                    'password' => new Record('1234', 'Database password'),
                ],
                [
                    'username' => new Record('user123', 'Database username'),
                    'password' => new Record('1234', 'Database password'),
                ],
            ],

            [
                [
                    'database' => [
                        'host' => 'localhost',
                        'port' => 3306,
                    ],
                ],
                [
                    'database' => new Record(
                        [
                            'host' => new Record('localhost'),
                            'port' => new Record(3306),
                        ]
                    ),
                ],
            ],

            [
                [
                    'database' => new Record(
                        [
                            'host' => 'localhost',
                            'port' => 3306,
                        ],
                        'Database settings'
                    ),
                ],
                [
                    'database' => new Record(
                        [
                            'host' => new Record('localhost'),
                            'port' => new Record(3306),
                        ],
                        'Database settings'
                    ),
                ],
            ],
        ];
    }

    public function providerAddRecord()
    {
        return [
            [
                'title',
                'My Website',
                'Website title',
                [
                    'title' => new Record('My Website', 'Website title'),
                ],
            ],
            [
                'website.title',
                'My Website',
                'Website title',
                [
                    'website' => new Record(
                        [
                            'title' => new Record('My Website', 'Website title'),
                        ]
                    ),
                ],
            ],
        ];
    }
}
