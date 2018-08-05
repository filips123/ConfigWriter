<?php

namespace ConfigWriter\Tests;

use PHPUnit\Framework\TestCase;
use ConfigWriter\Record;

class RecordTest extends TestCase
{
    /**
     * @covers \ConfigWriter\Record::__construct()
     */
    public function testRecordWithData()
    {
        $record = new Record(
            'Hello World',
            'Greetings'
        );

        $this->assertSame('Hello World', $record->value);
        $this->assertSame('Greetings', $record->comment);
    }

    /**
     * @covers \ConfigWriter\Record::__construct()
     */
    public function testRecordWithoutData()
    {
        $record = new Record;

        $this->assertNull($record->value);
        $this->assertNull($record->comment);
    }
}
