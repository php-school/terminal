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

    public function __construct($stream = \STDOUT)
    {
        try {
            $meta = stream_get_meta_data($this->stream)['mode'];
            if ($meta['mode'][0] != 'r' AND $meta['mode'][-1] != '+') {
                throw new \InvalidArgumentException('Expected a writable stream');
            }
        } catch(\TypeError $e){
            throw new \InvalidArgumentException('Expected a valid stream');
        }

        $this->stream = $stream;
    }

    public function write(string $buffer): void
    {
        fwrite($this->stream, $buffer);
    }

    /**
     * Whether the stream is connected to an interactive terminal
     */
    public function isInteractive() : bool
    {
        return posix_isatty($this->stream);
    }
}
