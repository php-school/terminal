<?php

declare(strict_types=1);

namespace PhpSchool\Terminal\IO;

class NonBlockingResourceInputStream implements InputStream
{
    /**
     * @var InputStream
     */
    private $innerStream;

    /**
     * @param resource $stream
     */
    public function __construct($stream = null)
    {
        $this->innerStream = new ResourceInputStream($stream ?? STDIN);
        stream_set_blocking($stream, false);
    }

    /**
     * @inheritDoc
     */
    public function read(int $numBytes, callable $callback): void
    {
        $this->innerStream->read($numBytes, $callback);
    }

    /**
     * @inheritDoc
     */
    public function isInteractive(): bool
    {
        return $this->innerStream->isInteractive();
    }
}
