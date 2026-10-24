<?php

namespace PhpSchool\Terminal\IO;

use function is_resource;
use function get_resource_type;
use function stream_get_meta_data;
use function strpos;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ResourceOutputStream implements OutputStream
{
    /**
     * @var resource
     */
    private $stream;

    /**
     * @param resource $stream
     */
    public function __construct($stream = \STDOUT)
    {
        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new \InvalidArgumentException('Expected a valid stream');
        }

        $meta = stream_get_meta_data($stream);
        if (strpos($meta['mode'], 'r') !== false && strpos($meta['mode'], '+') === false) {
            throw new \InvalidArgumentException('Expected a writable stream');
        }

        $this->stream = $stream;
    }

    public function write(string $buffer): void
    {
        // The stream may be non-blocking (on a TTY it shares a file description with STDIN,
        // which ResourceInputStream makes non-blocking), so fwrite can write only part of the buffer
        while ($buffer !== '') {
            $written = fwrite($this->stream, $buffer);
            if ($written === false) {
                return;
            }
            $buffer = substr($buffer, $written);
        }
    }

    /**
     * Whether the stream is connected to an interactive terminal
     */
    public function isInteractive(): bool
    {
        return stream_isatty($this->stream);
    }
}
