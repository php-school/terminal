<?php

namespace PhpSchool\Terminal;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
interface TerminalInterface
{
    /**
     * Get terminal details which can be used to identify
     */
    public function getDetails(): string;

    /**
     * Get the available width of the terminal
     */
    public function getWidth(): int;

    /**
     * Get the available height of the terminal
     */
    public function getHeight(): int;

    /**
     * Toggle canonical mode on TTY
     */
    public function setCanonicalMode(bool $useCanonicalMode = true);

    /**
     * Check if TTY is in canonical mode
     */
    public function isCanonical(): bool;

    /**
     * Test whether terminal is valid TTY
     */
    public function isTTY(): bool;

    /**
     * Test whether terminal supports colour output
     */
    public function supportsColour(): bool;

    /**
     * Clear the terminal window
     */
    public function clear();

    /**
     * Clear the current cursors line
     */
    public function clearLine();

    /**
     * Move the cursor to the top left of the window
     */
    public function moveCursorToTop();

    /**
     * Move the cursor to the start of a specific row
     */
    public function moveCursorToRow(int $rowNumber);

    /**
     * Move the cursor to a specific column
     */
    public function moveCursorToColumn(int $columnNumber);

    /**
     * Clean the whole console without jumping the window
     */
    public function clean();

    /**
     * Enable cursor display
     */
    public function enableCursor();

    /**
     * Disable cursor display
     */
    public function disableCursor();

    /**
     * Read keypress from terminal input
     */
    public function getKeyedInput(): KeypressInput;
}
