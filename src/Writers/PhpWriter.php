<?php

namespace ConfigWriter\Writers;

use ConfigWriter\Exceptions\WriteException;
use ConfigWriter\AbstractConfig;
use ConfigWriter\Record;

/**
 * Configuration writer for PHP.
 *
 * @since 2.0.0
 *
 * @author Filip Š <projects@filips.si>
 *
 * @license MIT
 *
 * @package ConfigWriter
 */
class PhpWriter implements WriterInterface
{
    /**
     * Stores default configuration options.
     *
     * @var array
     */
    protected $options = [
        'indentation' => '    ',
        'eol' => "\n",
        'boolean' => [
            'true' => 'true',
            'false' => 'false',
        ],
        'string' => [ 'quotes' => '\'' ],
        'array' => [
            'open' => '[',
            'close' => ']',
            'comma' => true,
        ],
        'comments' => [
            'section' => 'c-style',
            'record' => 'c-style',
        ],
    ];

    /**
     * Returns parsable string representation of a variable.
     *
     * @param mixed   $variable Variable for exporting
     * @param array   $options  Options for exporting
     *   * indentation: &nbsp;&nbsp;&nbsp;&nbsp; (string, default) - Indenttion string
     *   * eol:         \n                       (string, default) - End of line string
     *   * boolean (array)
     *     * true:  true  (string, default) - The true keyword
     *     * false: false (string, default) - The false keyword
     *   * string (array)
     *     * quotes: ' (string, default) - The string quotes
     *   * array (array)
     *     * open:  [    (string, default)  - The array open tag
     *     * close: ]    (string, default)  - The array close tag
     *     * comma: true (boolean, default) - Comma at the end of array
     *   * comments (array)
     *     * section: c-style ((c-style, perl-style, multi-line, doc-block), default) - The comment style for sections
     *     * record:  c-style ((c-style, perl-style, multi-line, doc-block), default) - The comment style for records
     * @param string  $level    Indentation level
     * @param boolean $comma    If comma should be added to result
     * @param string  $section  Comment of section
     *
     * @return string Exported data
     */
    protected function export($variable, $options = [], $level = 0, $comma = true, $section = '')
    {
        if ($variable instanceof AbstractConfig) {
            $value = $variable->data;
            $comment = $variable->comment;
        } elseif ($variable instanceof Record) {
            $value = $variable->value;
            $comment = $variable->comment;
        } else {
            $value = $variable;
            $comment = null;
        }

        switch (gettype($value)) {
            case 'boolean':
                $result = $value
                    ? $options['boolean']['true']
                    : $options['boolean']['false'];

                $result .= $comma
                    ? ','
                    : '';

                $result .= $comment
                    ? ' ' . $this->comment($comment, $options['comments']['record'])
                    : '';

                return $result;

            case 'string':
                if ($section) {
                    $result = $this->comment($section, $options['comments']['section'])
                        . $options['eol']
                        . str_repeat($options['indentation'], $level);
                } else {
                    $result = '';
                }

                $result .= $options['string']['quotes']
                    . addcslashes($value, "\\\$\"\r\n\t\v\f")
                    . $options['string']['quotes'];

                $result .= $comma
                    ? ','
                    : '';

                if (!$variable instanceof AbstractConfig && $comment) {
                    $result .= $comment
                    ? ' ' . $this->comment($comment, $options['comments']['record'])
                    : '';
                }

                return $result;

            case 'array':
                $indexed = array_keys($value) === range(0, (count($value) - 1));
                $keys = array_keys($value);
                $last = end($keys);
                $eol = $options['eol'];

                $arr = [];
                foreach ($value as $key => $val) {
                    if ($key !== $last || $options['array']['comma'] !== false) {
                        $comma = true;
                    } else {
                        $comma = false;
                    }

                    $arr[] = str_repeat($options['indentation'], $level)
                        . (
                            $indexed ? '' : $this->export(
                                $key,
                                $options,
                                $level,
                                false,
                                ($val instanceof AbstractConfig ? $val->comment : '')
                            ) . ' => '
                        )
                        . $this->export($val, $options, ($level + 1), true);
                }

                return '['
                    . $eol
                    . implode(
                        $eol,
                        $arr
                    )
                    . $eol
                    . str_repeat($options['indentation'], ($level - 1))
                    . ']'
                    . (
                        $options['array']['comma'] && !is_array($variable)
                        ? ','
                        : ''
                    );

            default:
                $result = var_export($value, true);

                $result .= $comma
                    ? ','
                    : '';

                $result .= $comment
                    ? ' ' . $this->comment($comment, $options['comments']['record'])
                    : '';

                return $result;
        }
    }

    /**
     * Returns comment of chosen style.
     *
     * @param string $data  Comment data.
     * @param array  $style Comment style.
     *
     * @return string Styled comment
     */
    protected function comment($data, $style)
    {
        if ($style === 'c-style') {
            $format = '// %s';
        } elseif ($style === 'perl-style') {
            $format = '# %s';
        } elseif ($style === 'multi-line') {
            $format = '/* %s */';
        } elseif ($style === 'doc-block') {
            $format = '/** %s */';
        } else {
            $format = '// %s';
        }

        return sprintf($format, $data);
    }

    /**
     * {@inheritDoc}
     *
     * Returns configuration as PHP array.
     */
    public function write(AbstractConfig $config, $options = [])
    {
        $options = array_replace_recursive($this->options, $options);
        $eol = $options['eol'];

        $converted = '<?php' . $eol . $eol;
        $converted .= (!empty($config->comment) ? $config->comment . $eol . $eol : '');
        $converted .= 'return ';
        $converted .= $this->export($config->data, $options, 1);
        $converted .= ';' . $eol;

        return $converted;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSupportedExtensions()
    {
        return ['php'];
    }
}
