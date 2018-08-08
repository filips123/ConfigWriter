<?php

namespace ConfigWriter;

use ConfigWriter\Exceptions\UnsupportedFormatException;
use ConfigWriter\Exceptions\WriteException;
use ConfigWriter\Writers\WriterInterface;

/**
 * Configuration class.
 *
 * @since 2.0.0
 *
 * @author Filip Š <projects@filips.si>
 *
 * @license MIT
 *
 * @package ConfigWriter
 */
class Config extends AbstractConfig
{
    /**
     * Stores the supported writers.
     *
     * @var array
     */
    protected $supportedWriters = [];

    /**
     * Writes configuration to string.
     *
     * @param WriterInterface $writer  Configuration writer
     * @param array           $options Writer options (optional)
     *
     * @return string Encoded configuration string
     *
     * @throws WriteException If there is an error while writing a string
     */
    public function toString(WriterInterface $writer, $options = [])
    {
        return $writer->write($this, $options);
    }

    /**
     * Writes configuration to file.
     *
     * @param string          $filename Configuration file name
     * @param WriterInterface $writer   Configuration writer (optional)
     * @param array           $options  Writer options (optional)
     *
     * @return void
     *
     * @throws UnsupportedFormatException If file extension is unsupported
     * @throws WriteException             If there is an error while writing a file
     */
    public function toFile($filename, WriterInterface $writer = null, $options = [])
    {
        if ($writer === null) {
            // Get file information
            $info = pathinfo($filename);
            $parts = explode('.', $info['basename']);
            $extension = array_pop($parts);

            // Skip the `dist` extension
            if ($extension === 'dist') {
                $extension = array_pop($parts);
            }

            // Get configuration writer
            $writer = $this->getWriter($extension);
        }

        $data = $this->toString($writer, $options);

        // @codeCoverageIgnoreStart
        if (!is_dir(dirname($filename))) {
            mkdir(dirname($filename), 0777, true);
        }
        // @codeCoverageIgnoreEnd

        file_put_contents($filename, $data);
    }

    /**
     * Gets a writer for a given file extension.
     *
     * @param string $extension File extension
     *
     * @return WriterInterface Writer for a given file extension
     *
     * @throws UnsupportedFormatException If `$extension` is an unsupported file format
     */
    protected function getWriter($extension)
    {
        foreach ($this->supportedWriters as $writer) {
            if (in_array($extension, $writer::getSupportedExtensions())) {
                return new $writer();
            }
        }

        throw new UnsupportedFormatException('Unsupported configuration format');
    }
}
