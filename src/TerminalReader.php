<?php

namespace PhpSchool\Terminal;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class TerminalReader
{
    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * Map of characters to controls.
     * Eg map 'w' to the up control.
     *
     * @var array
     */
    private $mappings = [];

    public function __construct(Terminal $terminal)
    {
        $this->terminal = $terminal;
    }

    public function addControlMapping(string $character, string $mapToControl) : void
    {
        if (!InputCharacter::controlExists($mapToControl)) {
            throw new \InvalidArgumentException(sprintf('Control "%s" does not exist', $mapToControl));
        }

        $this->mappings[$character] = $mapToControl;
    }

    public function addControlMappings(array $mappings) : void
    {
        foreach ($mappings as $character => $mapToControl) {
            $this->addControlMapping($character, $mapToControl);
        }
    }

    public function readCharacter() : InputCharacter
    {
        $char = $this->terminal->readCharacter();

        if (isset($this->mappings[$char])) {
            return InputCharacter::fromControlName($this->mappings[$char]);
        }

        return new InputCharacter($char);
    }
}
