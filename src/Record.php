<?php

namespace ConfigWriter;

use ArrayAccess;

/**
 * Record class for configuration data.
 *
 * It stores record's value and comment.
 *
 * @since 2.0.0
 *
 * @author Filip Š <projects@filips.si>
 *
 * @license MIT
 *
 * @package ConfigWriter
 */
class Record implements ArrayAccess
{
    /**
     * @var mixed|null Record's value.
     */
    public $value;

    /**
     * @var mixed|null Record's comment.
     */
    public $comment;

    /**
     * Constructor for record class.
     *
     * It stores record's key, value and comment.
     *
     * @param mixed|null $value   Record's value (optional)
     * @param mixed|null $comment Record's comment (optional)
     */
    public function __construct($value = null, $comment = null)
    {
        $this->value = $value;
        $this->comment = $comment;
    }

    /**
     * Gets a value using the offset as a key.
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        return $this->value[$offset];
    }
    /**
     * Checks if a key exists.
     *
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->value[$offset]);
    }
    /**
     * Sets a value using the offset as a key.
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->value[$offset] = $value;
    }
    /**
     * Deletes a key and its value
     *
     * @param string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->value[$offset] = null;
    }
}
