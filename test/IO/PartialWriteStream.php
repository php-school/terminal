<?php

namespace PhpSchool\TerminalTest\IO;

/**
 * Alternates between accepting 2 bytes and accepting nothing, like a full non-blocking TTY
 */
class PartialWriteStream
{
    public static string $contents = '';

    private bool $full = false;

    /** @var resource|null */
    public $context;

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName
    public function stream_open(string $path, string $mode): bool
    {
        self::$contents = '';
        return true;
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName
    public function stream_write(string $data): int
    {
        $this->full = !$this->full;
        if (!$this->full) {
            return 0;
        }

        $chunk = substr($data, 0, 2);
        self::$contents .= $chunk;
        return strlen($chunk);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName
    public function stream_eof(): bool
    {
        return false;
    }
}
