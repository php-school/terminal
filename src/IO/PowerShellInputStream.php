<?php

namespace PhpSchool\Terminal\IO;

use function is_resource;
use function get_resource_type;
use function stream_get_meta_data;
use function strpos;

/**
 * @author Chung Leong <chernyshevsky@hotmail.com>
 */
class PowerShellInputStream implements InputStream
{
    protected $charMappings = [
        "\r" => "\n",
    ];
    protected $keyMappings = [
        38 => "\033[A",
        40 => "\033[B",
        39 => "\033[C",
        37 => "\033[D",
    ];

    public function read(int $numBytes, callable $callback) : void
    {
        // Call PowerShell's ReadKey API. 
        // True hides the pressed character from duplicating on the screen.
        $cmd = 'powershell.exe -NoProfile -Command "[Console]::ReadKey($true) | ConvertTo-Json"';
        
        // Open a single-byte fast pipeline execution
        $handle = popen($cmd, 'r');
        $response = stream_get_contents($handle);
        pclose($handle);

        // Parse the resulting JSON data from PowerShell
        if ($key_info = json_decode($response, true)) {
            $char = $key_info['KeyChar'] ?? '';
            $name = $key_info['Key'] ?? '';
            $modifiers = $key_info['Modifiers'] ?? 0;
            $data = $this->keyMappings[$name] ?? $this->charMappings[$char] ?? $char;
            if ($data) {
                $callback($data);
            }
        }
    }

    public function isInteractive() : bool
    {
        return true;
    }
}
