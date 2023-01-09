<?php

namespace PhpSchool\Terminal\IO;

use function is_resource;
use function get_resource_type;
use function stream_get_meta_data;
use function strpos;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ResourceInputStream implements InputStream
{
    /**
     * @var resource
     */
    private $stream;

    /**
     * @param resource $stream
     */
    public function __construct($stream = null)
    {
        $stream = $stream ? $stream : STDIN;

        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new \InvalidArgumentException('Expected a valid stream');
        }

        $meta = stream_get_meta_data($stream);
        if (strpos($meta['mode'], 'r') === false && strpos($meta['mode'], '+') === false) {
            throw new \InvalidArgumentException('Expected a readable stream');
        }

        $this->stream = $stream;
    }

    public function read(int $numBytes, callable $callback) : void
    {
        $buffer = fread($this->stream, $numBytes);
        if (!empty($buffer)) {
            // Prevent blocking to handle pasted input.
            stream_set_blocking($this->stream, false);
        } else {
            // Re-enable blocking when input has been handled.
            stream_set_blocking($this->stream, true);
        }

        $callback($buffer);
    }

    /**
     * Whether the stream is connected to an interactive terminal
     */
    public function isInteractive() : bool
    {
        return posix_isatty($this->stream);
    }
}
