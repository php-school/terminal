<?php

namespace PhpSchool\Terminal;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class Terminal implements TerminalInterface
{
    /**
     * @var bool
     */
    private $isTTY;

    /**
     * @var bool
     */
    private $isCanonical = false;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var string
     */
    private $details;

    /**
     * @var string
     */
    private $originalConfiguration;

    public function __construct()
    {
        $this->getOriginalConfiguration();
    }

    public function getWidth(): int
    {
        return $this->width ?: $this->width = (int) exec('tput cols');
    }

    public function getHeight(): int
    {
        return $this->height ?: $this->height = (int) exec('tput lines');
    }

    public function getDetails(): string
    {
        if (!$this->details) {
            $this->details = function_exists('posix_ttyname')
                ? @posix_ttyname(STDOUT)
                : "Can't retrieve terminal details";
        }

        return $this->details;
    }

    private function getOriginalConfiguration(): string
    {
        return $this->originalConfiguration ?: $this->originalConfiguration = exec('stty -g');
    }

    public function setCanonicalMode(bool $useCanonicalMode = true)
    {
        if ($useCanonicalMode) {
            exec('stty -icanon');
            $this->isCanonical = true;
        } else {
            exec('stty ' . $this->getOriginalConfiguration());
            $this->isCanonical = false;
        }
    }

    public function isCanonical(): bool
    {
        return $this->isCanonical;
    }

    public function isTTY(): bool
    {
        return $this->isTTY ?: $this->isTTY = function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }

    /**
     * @see https://github.com/symfony/Console/blob/master/Output/StreamOutput.php#L95-L102
     */
    public function supportsColour(): bool
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI') || 'xterm' === getenv('TERM');
        }

        return $this->isTTY();
    }

    public function getKeyedInput(): KeypressInput
    {
        $map = [
            "\033[A" => KeypressInput::UP(),
            "k"      => KeypressInput::UP(),
            "\033[B" => KeypressInput::DOWN(),
            "j"      => KeypressInput::DOWN(),
            "\n"     => KeypressInput::ENTER(),
            "\r"     => KeypressInput::ENTER(),
            " "      => KeypressInput::ENTER(),
        ];

        $input = fread(STDIN, 4);
        $this->clearLine();

        return array_key_exists($input, $map)
            ? $map[$input]
            : $input;
    }

    public function clear()
    {
        echo "\033[2J";
    }

    public function enableCursor()
    {
        echo "\033[?25h";
    }

    public function disableCursor()
    {
        echo "\033[?25l";
    }

    public function moveCursorToTop()
    {
        echo "\033[H";
    }

    public function moveCursorToRow(int $rowNumber)
    {
        echo sprintf("\033[%d;0H", $rowNumber);
    }

    public function moveCursorToColumn(int $column)
    {
        echo sprintf("\033[%dC", $column);
    }

    public function clearLine()
    {
        echo sprintf("\033[%dD\033[K", $this->getWidth());
    }

    public function clean()
    {
        foreach (range(0, $this->getHeight()) as $rowNum) {
            $this->moveCursorToRow($rowNum);
            $this->clearLine();
        }
    }
}
