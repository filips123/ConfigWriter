<?php

namespace ConfigWriter\Writers;

use ConfigWriter\Exceptions\WriteException;
use ConfigWriter\AbstractConfig;

/**
 * Configuration writer interface.
 *
 * @since 2.0.0
 *
 * @author Filip Š <projects@filips.si>
 *
 * @license MIT
 *
 * @package ConfigWriter
 */

interface WriterInterface
{
    /**
     * Returns configuration from `$config` as encoded string.
     *
     * @param AbstractWriter $config  Configuration
     * @param array          $options Writer options (optional)
     *
     * @return string Encoded configuration string
     *
     * @throws WriteException If there is an error while writing a string
     */
    public function write(AbstractConfig $config, $options = []);

    /**
     * Returns an array of allowed file extensions for this writer.
     *
     * @return array Array of file extensions
     */
    public static function getSupportedExtensions();
}
