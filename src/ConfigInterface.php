<?php

namespace ConfigWriter;

/**
 * Configuration interface.
 *
 * @since 2.0.0
 *
 * @author Filip Š <projects@filips.si>
 *
 * @license MIT
 *
 * @package ConfigWriter
 */
interface ConfigInterface
{
    /**
     * Adds configuration record.
     *
     * @param string     $key     Record's key
     * @param mixed|null $value   Record's value (optional)
     * @param mixed|null $comment Record's comment (optional)
     *
     * @return Record Added record
     */
    public function addRecord($key, $value = null, $comment = null);

    /**
     * Adds configuration section.
     *
     * @param string     $key     Section's key
     * @param array      $data    Section's data (optional)
     * @param mixed|null $comment Section's comment (optional)
     *
     * @return ConfigInterface Added section
     */
    public function addSection($key, $data = [], $comment = null);
}
