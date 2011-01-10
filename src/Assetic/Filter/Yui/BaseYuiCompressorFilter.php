<?php

namespace Assetic\Filter\Yui;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

/**
 * Base YUI compressor filter.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
abstract class BaseYuiCompressorFilter implements FilterInterface
{
    private $yuiCompressorPath;
    private $javaPath;
    private $charset = 'utf-8';
    private $lineBreak;

    public function __construct($yuiCompressorPath, $javaPath = '/usr/bin/java')
    {
        $this->yuiCompressorPath = $yuiCompressorPath;
        $this->javaPath = $javaPath;
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    public function setLineBreak($lineBreak)
    {
        $this->lineBreak = $lineBreak;
    }

    public function filterLoad(AssetInterface $asset)
    {
    }

    /**
     * Compresses a string.
     *
     * @param string $content The content to compress
     * @param string $type    The type of content, either "js" or "css"
     * @param array  $options An indexed array of additional options
     *
     * @return string The compressed content
     */
    protected function compress($content, $type, $options = array())
    {
        // prepend the start of the command
        $options = array_merge(array(
            $this->javaPath,
            '-jar',
            $this->yuiCompressorPath,
            '--type',
            $type,
        ), $options);

        if (null !== $this->charset) {
            $options[] = '--charset';
            $options[] = $this->charset;
        }

        if (null !== $this->lineBreak) {
            $options[] = '--line-break';
            $options[] = $this->lineBreak;
        }

        $options[] = $input = tempnam(sys_get_temp_dir(), 'assetic');
        file_put_contents($input, $content);

        // todo: check for a valid return code
        $output = shell_exec(implode(' ', array_map('escapeshellarg', $options)));

        // cleanup
        unlink($input);

        return $output;
    }
}
