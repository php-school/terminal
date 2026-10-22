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
     * @var bool Original blocking state.
     */
    private $blocking;

    /**
     * @param resource $stream
     */
    public function __construct($stream = \STDIN)
    {
        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new \InvalidArgumentException('Expected a valid stream');
        }

        /** @var array<string, mixed> $meta Not every stream type (eg php://memory) reports 'blocked' */
        $meta = stream_get_meta_data($stream);
        if (strpos($meta['mode'], 'r') === false && strpos($meta['mode'], '+') === false) {
            throw new \InvalidArgumentException('Expected a readable stream');
        }

        $this->blocking = (bool) ($meta['blocked'] ?? true);
        $this->stream = $stream;
    }

    /**
     * Restore the blocking state.
     */
    public function __destruct()
    {
        if (is_resource($this->stream)) {
            stream_set_blocking($this->stream, $this->blocking);
        }
    }

    public function read(int $numBytes, callable $callback): void
    {
        if ($numBytes < 1) {
            throw new \InvalidArgumentException('Number of bytes to read must be greater than 0');
        }

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
    public function isInteractive(): bool
    {
        return stream_isatty($this->stream);
    }
}
