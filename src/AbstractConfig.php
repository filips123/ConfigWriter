<?php

namespace ConfigWriter;

use RecursiveIteratorIterator;
use RecursiveArrayIterator;
use ArrayAccess;

/**
 * Abstract configuration class.
 *
 * @since 2.0.0
 *
 * @author Filip Š <projects@filips.si>
 *
 * @license MIT
 *
 * @package ConfigWriter
 */
abstract class AbstractConfig implements ConfigInterface, ArrayAccess
{
    /**
     * Stores the configuration data.
     *
     * @var array;
     */
    public $data = [];

    /**
     * Stores the configuration comment.
     *
     * @var mixed|null
     */
    public $comment = null;

    /**
     * Constructs the configuration.
     *
     * @param array  $data    Configuration data  (optional)
     * @param string $comment Configuration comment  (optional)
     */
    public function __construct($data = [], $comment = null)
    {
        $ritit = new RecursiveIteratorIterator(
            new RecursiveArrayIterator(
                $data,
                RecursiveArrayIterator::CHILD_ARRAYS_ONLY
            )
        );

        foreach ($ritit as $leafValue) {
            $keys = [];

            foreach (range(0, $ritit->getDepth()) as $depth) {
                $keys[] = $ritit->getSubIterator($depth)->key();
            }

            $this->set(join('.', $keys), $leafValue);
        }

        $this->data = self::stringToRecord($this->data);
        $this->comment = $comment;
    }

    /**
     * Changes string to config record.
     *
     * @param array $arr Array with configuration
     *
     * @return array Converted array with configuration
     */
    public static function stringToRecord($arr)
    {
        $result = [];

        foreach ($arr as $key => $value) {
            if ($value instanceof Record) {
                if (is_array($value->value)) {
                    $result[$key] = new Record(
                        self::stringToRecord($value->value),
                        $value->comment
                    );
                } else {
                    $result[$key] = new Record(
                        $value->value,
                        $value->comment
                    );
                }
            } elseif ($value instanceof static) {
                $result[$key] = $value;
            } else {
                if (is_array($value)) {
                    $result[$key] = new Record(
                        self::stringToRecord($value)
                    );
                } else {
                    $result[$key] = new Record(
                        $value
                    );
                }
            }
        }

        return $result;
    }

    /**
     * Changes config record to string.
     *
     * @param array $arr Array with configuration
     *
     * @return array Converted array with configuration
     */
    public static function recordToString($arr)
    {
        $result = [];

        foreach ($arr as $key => $value) {
            if ($value instanceof Record) {
                if (is_array($value->value)) {
                    $result[$key] = self::recordToString($value->value);
                } else {
                     $result[$key] = $value->value;
                }
            } elseif ($value instanceof static) {
                $result = array_merge($result, self::recordToString($value->data));
            } else {
                if (is_array($value)) {
                    $result[$key] = self::recordToString($value);
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Function for setting configuration values, using
     * either simple or nested keys.
     *
     * @param string $key   Record's key
     * @param mixed  $value Record's value
     *
     * @return mixed Added record
     */
    protected function set($key, $value)
    {
        $segs = explode('.', $key);
        $root = &$this->data;
        $cacheKey = '';

        // Look for the key, creating nested keys if needed
        while ($part = array_shift($segs)) {
            if ($cacheKey !== '') {
                $cacheKey .= '.';
            }
            $cacheKey .= $part;

            if (!isset($root[$part]) && count($segs)) {
                $root[$part] = [];
            }
                $root = &$root[$part];
        }

        // Assign value at target node
        $root = $value;

        return $value;
    }

    /**
     * ConfigInterface methods.
     */

    /**
     * {@inheritDoc}
     */
    public function addRecord($key, $value = null, $comment = null)
    {
        $record = $this->set(
            $key,
            new Record($value, $comment)
        );

        $this->data = self::stringToRecord($this->data);

        return $record;
    }

    /**
     * {@inheritDoc}
     */
    public function addSection($key, $data = [], $comment = null)
    {
        $section = $this->set(
            $key,
            new static(
                $data,
                $comment
            )
        );

        $this->data = self::stringToRecord($this->data);

        return $section;
    }

    /**
     * ArrayAccess methods.
     */

    /**
     * Gets a value using the offset as a key.
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        // @codeCoverageIgnoreStart
        if (!isset($this->data[$offset])) {
            $this->data[$offset] = null;
        }
        // @codeCoverageIgnoreEnd

        return $this->data[$offset];
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
        return isset($this->data[$offset]);
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
        if ($value instanceof Record || $value instanceof ConfigInterface) {
            $this->set($offset, $value);
        } else {
            $this->addRecord($offset, $value);
        }
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
        $this->set($offset, null);
    }
}
